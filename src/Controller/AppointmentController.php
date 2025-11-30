<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Appointment;
use App\Entity\AppointmentSlot;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Repository\DoctorRepository;
use App\Repository\PatientRepository;
use App\Repository\TreatmentRepository;
use App\Repository\AppointmentRepository;

class AppointmentController extends AbstractController
{
    #[Route('/api/appointment/coming', name: 'api_appointment_coming', methods: ['GET'])]
    public function comingAppointment(ManagerRegistry $doctrine): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $appointments = $doctrine->getRepository(Appointment::class)->findBy(['patient' => $user]);

        $now = new \DateTimeImmutable();
        $upcoming = [];

        foreach ($appointments as $appointment) {
            if ($appointment->getDateTime() > $now) {
                $upcoming[] = [
                    'id' => $appointment->getId(),
                    'title' => $appointment->getTitle(),
                    'dateTime' => $appointment->getDateTime()->format('d/m/Y \à H\hi'),
                    'doctor' => $appointment->getDoctor()
                        ? [
                            'lastname' => $appointment->getDoctor()->getLastname(),
                            'name' => $appointment->getDoctor()->getCenter()->getName()
                        ]
                        : null,
                ];
            }
        }

    return $this->json($upcoming);

    }

    #[Route('/api/appointment/past', name: 'api_appointment_past', methods: ['GET'])]
    public function pastAppointment(ManagerRegistry $doctrine): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $appointments = $doctrine->getRepository(Appointment::class)->findBy(['patient' => $user]);

        $now = new \DateTimeImmutable();
        $upcoming = [];

        $medicinesArray = [];
        $prescriptionsArray = [];

        foreach ($appointments as $appointment) {
            if ($appointment->getDateTime() < $now) {
                foreach ($appointment->getPrescriptions() as $prescription) {
                    foreach ($prescription->getMedicines() as $medicine) {
                        $medicinesArray[] = [
                        'id' => $medicine->getId(),
                        'name' => $medicine->getName(),
                        'frequency' => $medicine->getFrequency(),
                        'duration' => $medicine->getDuration()
                        ];
                    }

                    $prescriptionsArray[] = [
                        'id' => $prescription->getId(),
                        'report' => $prescription->getReport(),
                        'prescriptionDetails' => $prescription->getPrescriptionDetails(),
                        'medicine' => $medicinesArray,
                    ];
                }

                $past[] = [
                    'title' => $appointment->getTitle(),
                    'dateTime' => $appointment->getDateTime()->format('d/m/Y \à H\hi'),
                    'doctor' => $appointment->getDoctor()
                        ? [
                            'lastname' => $appointment->getDoctor()->getLastname(),
                            'name' => $appointment->getDoctor()->getCenter()->getName()
                        ]
                        : null,
                    'prescriptions' => $prescriptionsArray,
                ];
            }
        }

    return $this->json($past);

    }

    #[Route('/api/appointment/create', name: 'api_appointment_create', methods: ['POST'])]
    public function bookAppointment(
        Request $request,
        EntityManagerInterface $em,
        PatientRepository $patientRepo,
        DoctorRepository $doctorRepo,
        TreatmentRepository $treatmentRepo
    ): JsonResponse {

        $data = json_decode($request->getContent(), true);

        $patientId = basename($data['patient']);
        $doctorId = basename($data['doctor']);
        $treatmentId = basename($data['treatments'][0]);

        $patient = $patientRepo->find($patientId);
        $doctor = $doctorRepo->find($doctorId);
        $treatment = $treatmentRepo->find($treatmentId);

        if (!$patient || !$doctor || !$treatment) {
            return new JsonResponse(['error' => 'Patient, doctor or treatment not found'], 400);
        }

        $appointment = new Appointment();
        $appointment->setTitle($data['title']);
        $appointment->setPatient($patient);
        $appointment->setDoctor($doctor);
        $appointment->addTreatment($treatment);

        $dateTime = \DateTime::createFromFormat('Y-m-d\TH:i:s', $data['dateTime']);
        if (!$dateTime) {
            return new JsonResponse(['error' => 'Invalid dateTime format'], 400);
        }
        $appointment->setDateTime($dateTime);

        $em->persist($appointment);
        $em->flush();

        $durationMinutes = $treatment->getDuration();

        $startTime = \DateTime::createFromFormat('Y-m-d H:i:s', $appointment->getDateTime()->format('Y-m-d H:i:s'));
        $endTime = (clone $startTime)->modify("+{$durationMinutes} minutes");

        $slot = new AppointmentSlot();
        $slot->setStartTime($startTime);
        $slot->setEndTime($endTime);
        $slot->setAppointment($appointment);

        $em->persist($slot);
        $em->flush();

        return new JsonResponse(['success' => true], 201);
    }
}