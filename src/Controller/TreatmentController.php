<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Treatment;

class TreatmentController extends AbstractController
{
    #[Route('/api/treatments/list', name: 'api_treatments_list', methods: ['GET'])]
    public function listTreatments(ManagerRegistry $doctrine): JsonResponse
    {
        $treatments = $doctrine->getRepository(Treatment::class)->findAll();

        $grouped = [];

        foreach ($treatments as $treatment) {
            $cat = $treatment->getCategory() ?: 'Autre';
            if (!isset($grouped[$cat])) {
                $grouped[$cat] = [];
            }
            $grouped[$cat][] = [
                'id' => $treatment->getId(),
                'name' => $treatment->getName(),
                'description' => $treatment->getDescription(),
                'price' => $treatment->getPrice(),
                'duration' => $treatment->getDuration()
            ];
        }

        return $this->json($grouped);
    }
}