<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
        return new JsonResponse($upcoming);
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

        return new JsonResponse($past);
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

        return new JsonResponse(['success' => true, 'appointmentId' => $appointment->getId()],Response::HTTP_CREATED);
    }

    #[Route('/api/appointment/{id}', name: 'api_appointment_update', methods: ['PATCH'])]
    public function updateAppointment(int $id, Request $request, EntityManagerInterface $em): JsonResponse 
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $appointment = $em->getRepository(Appointment::class)->find($id);
        if (!$appointment) {
            return $this->json(['error' => 'Appointment not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['error' => 'Invalid JSON'], 400);
        }

        $newDate = $data['date'] ?? null;
        $newTime = $data['time'] ?? null;

        if (!$newDate || !$newTime) {
            return $this->json(['error' => 'Date and time required'], 400);
        }

        $doctor = $appointment->getDoctor();

        $newStartDate = new \DateTimeImmutable($newDate);
        $newStartTime = new \DateTimeImmutable($newTime);

        $treatment = $appointment->getTreatments()->first();
        $duration = $treatment->getDuration();

        $newEndTime = $newStartTime->modify("+{$duration} minutes");

        $existingSlot = $em->getRepository(AppointmentSlot::class)->findOneBy([
            'doctor' => $doctor,
            'startDate' => $newStartDate,
            'startTime' => $newStartTime,
            'isBooked' => true
        ]);

        if ($existingSlot) {
            return $this->json(['error' => 'Slot already booked'], 409);
        }

        foreach ($appointment->getAppointmentSlots() as $oldSlot) {
            $em->remove($oldSlot);
        }

        $newSlot = new AppointmentSlot();
        $newSlot->setDoctor($doctor)
            ->setAppointment($appointment)
            ->setStartDate($newStartDate)
            ->setEndDate($newStartDate)
            ->setStartTime($newStartTime)
            ->setEndTime($newEndTime)
            ->setIsBooked(true);

        $em->persist($newSlot);

        $appointment->setDate($newStartDate);
        $appointment->setTime($newStartTime);

        $em->flush();

        return $this->json([
            'success' => true,
            'appointment' => [
                'id' => $appointment->getId(),
                'date' => $appointment->getDate()?->format('Y-m-d'),
                'time' => $appointment->getTime()?->format('H:i:s'),
            ]
        ]);
    }
}