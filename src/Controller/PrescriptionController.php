<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

        $prescriptions = $doctrine->getRepository(Prescription::class)->findBy(['patient' => $user]);

        $prescriptionList = [];

        foreach ($prescriptions as $prescription) {
            $prescriptionList[] = [
            'report' => $prescription->getReport(),
            'prescriptionDetails' => $prescription->getPrescriptionDetails(),
            'attendingPhysician' => $prescription->getAttendingPhysician()
            ? [
                'lastname' => $prescription->getAttendingPhysician()->getLastname()
            ]
            : null,
            'treatment' => $prescription->getTreatment()
            ? [
                'dateTime' => $prescription->getTreatment()->getAppointment()->getDateTime()->format('d/m/Y')
            ]
            : null,
            ];
        }

        return $this->json($prescriptionList);
    }
}