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
                'price' => $treatment->getPrice(),
                'duration' => $treatment->getDuration()
            ];
        }

        return $this->json($grouped);
    }

    #[Route('/api/treatments/search', name: 'api_treatments_search', methods: ['GET'])]
    public function searchTreatments(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $query = $request->query->get('query', '');
    /** @var \App\Repository\TreatmentRepository $repo */
    $repo = $doctrine->getRepository(Treatment::class);

    $treatments = $repo->createQueryBuilder('t')
        ->where('t.name LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult();
        
        $data = array_map(fn($t) => [
            'id' => $t->getId(),
            'name' => $t->getName()
        ], $treatments);

        return $this->json($data);
    }
}