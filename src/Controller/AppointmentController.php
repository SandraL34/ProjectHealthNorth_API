<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Appointment;

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
}