<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
                    'dateTime' => $appointment->getDateTime()->format('Y-m-d H:i'),
                    'institutionType' => $appointment->getInstitutionType(),
                    'specialtyType' => $appointment->getSpecialtyType(),
                    'attendingPhysician' => $appointment->getAttendingPhysician()
                        ? [
                            'firstname' => $appointment->getAttendingPhysician()->getFirstname(),
                            'lastname' => $appointment->getAttendingPhysician()->getLastname()
                        ]
                        : null,
                ];
            }
        }

    return $this->json($upcoming);

    }
}