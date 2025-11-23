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

        foreach ($appointments as $appointment) {
            if ($appointment->getDateTime() < $now) {
                $past[] = [
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

    return $this->json($past);

    }
}