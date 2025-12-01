<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Appointment;
use App\Entity\AppointmentSlot;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PatientRepository;
use App\Repository\DoctorRepository;
use App\Repository\TreatmentRepository;

class AppointmentController extends AbstractController
{
    #[Route('/api/appointment/coming', name: 'api_appointment_coming', methods: ['GET'])]
    public function comingAppointment(ManagerRegistry $doctrine): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) return $this->json(['error' => 'Unauthorized'], 401);

        $appointments = $doctrine->getRepository(Appointment::class)->findBy(['patient' => $user]);
        $now = new \DateTimeImmutable();
        $upcoming = [];

        foreach ($appointments as $appointment) {
            if ($appointment->getDate() > $now) {
                $upcoming[] = [
                    'id' => $appointment->getId(),
                    'title' => $appointment->getTitle(),
                    'date' => $appointment->getDate()?->format('Y-m-d'),
                    'time' => $appointment->getTime()?->format('H:i:s'),
                    'doctor' => $appointment->getDoctor() ? [
                        'lastname' => $appointment->getDoctor()->getLastname(),
                        'name' => $appointment->getDoctor()->getCenter()?->getName()
                    ] : null,
                ];
            }
        }
        return $this->json($upcoming);
    }

    #[Route('/api/appointment/past', name: 'api_appointment_past', methods: ['GET'])]
    public function pastAppointment(ManagerRegistry $doctrine): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) return $this->json(['error' => 'Unauthorized'], 401);

        $appointments = $doctrine->getRepository(Appointment::class)->findBy(['patient' => $user]);
        $now = new \DateTimeImmutable();
        $past = [];

        foreach ($appointments as $appointment) {
            if ($appointment->getDate() < $now) {
                $prescriptionsArray = [];
                foreach ($appointment->getPrescriptions() as $prescription) {
                    $medicinesArray = [];
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
                    'date' => $appointment->getDate()?->format('Y-m-d'),
                    'time' => $appointment->getTime()?->format('H:i:s'),
                    'doctor' => $appointment->getDoctor() ? [
                        'lastname' => $appointment->getDoctor()->getLastname(),
                        'name' => $appointment->getDoctor()->getCenter()?->getName()
                    ] : null,
                    'prescriptions' => $prescriptionsArray,
                ];
            }
        }

        return $this->json($past);
    }

    #[Route('/api/appointment/create', name: 'api_appointment_create', methods: ['POST'])]
    public function bookAppointment(Request $request, EntityManagerInterface $em, PatientRepository $patientRepo,
    DoctorRepository $doctorRepo, TreatmentRepository $treatmentRepo): JsonResponse 
    {
        $user = $this->getUser();
        if (!$user) return $this->json(['error' => 'Unauthorized'], 401);

        $data = json_decode($request->getContent(), true);

        $patient = $patientRepo->find(basename($data['patient']));
        $doctor = $doctorRepo->find(basename($data['doctor']));
        $treatment = $treatmentRepo->find(basename($data['treatments'][0]));

        if (!$patient || !$doctor || !$treatment) {
            return $this->json(['error' => 'Patient, doctor or treatment not found'], 400);
        }

        $appointment = new Appointment();
        $appointment->setTitle($data['title'])
                    ->setPatient($patient)
                    ->setDoctor($doctor)
                    ->addTreatment($treatment)
                    ->setDate(new \DateTimeImmutable($data['date']))
                    ->setTime(new \DateTimeImmutable($data['time']));

        $em->persist($appointment);

        $slot = new AppointmentSlot();
        $slot->setStartTime(new \DateTimeImmutable($data['time']))
            ->setEndTime((new \DateTimeImmutable($data['time']))->modify("+{$treatment->getDuration()} minutes"))
            ->setAppointment($appointment)
            ->setDoctor($doctor)
            ->setStartDate(new \DateTimeImmutable($data['date']))
            ->setEndDate(new \DateTimeImmutable($data['date']))
            ->setIsBooked(true);

        $em->persist($slot);
        $em->flush();

        return $this->json(['success' => true, 'appointmentId' => $appointment->getId()], 201);
    }
}