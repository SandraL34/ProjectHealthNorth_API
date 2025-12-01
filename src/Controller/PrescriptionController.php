<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Prescription;

class PrescriptionController extends AbstractController
{
    #[Route('/api/prescription', name: 'api_prescription', methods: ['GET'])]
    public function getPrescription(ManagerRegistry $doctrine): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $repo = $doctrine->getRepository(Prescription::class);
        /** @var \App\Repository\PrescriptionRepository $repo */

        $prescriptions = $repo->findByPatient($user);

        $prescriptionList = [];

        foreach ($prescriptions as $prescription) {
            $prescriptionList[] = [
                'id' => $prescription->getId(),
                'report' => $prescription->getReport(),
                'prescriptionDetails' => $prescription->getPrescriptionDetails(),
                'appointment' => $prescription->getAppointment()
                    ? [
                        'lastname' => $prescription->getAppointment()->getDoctor()->getLastname(),
                        'date' => $prescription->getAppointment()->getDate()?->format('Y-m-d'),
                        'time' => $prescription->getAppointment()->getTime()?->format('H:i:s'),
                    ]
                    : null,
            ];
        }

        return $this->json($prescriptionList);
    }
}