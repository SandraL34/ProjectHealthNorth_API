<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Center;

class CenterController extends AbstractController
{
    #[Route('/api/centers/search', name: 'api_centers_search', methods: ['GET'])]
    public function searchCentersWhat(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $query = $request->query->get('query', '');
    /** @var \App\Repository\CenterRepository $repo */
    $repo = $doctrine->getRepository(Center::class);

    $centers = $repo->createQueryBuilder('d')
        ->where('d.name LIKE :query OR d.address LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult();
        
        $data = array_map(fn($d) => [
            'id' => $d->getId(),
            'name' => $d->getName(),
        ], $centers);

        return $this->json($data);
    }

    #[Route('/api/centers/map', name:'api_centers_map', methods: ['GET'])]
    public function getCentersForMap(ManagerRegistry $doctrine): JsonResponse
    {
        $centers = $doctrine->getRepository(Center::class)->findAll();

        $list = [];

        foreach ($centers as $center) {
            $list[]= [
                'id' => $center->getId(),
                'name' => $center->getName(),
                'address' => $center->getAddress(),
                'latitude' => $center->getLatitude(),
                'longitude' => $center->getLongitude(),
                'doctors' => array_map(function ($doctor) {
                    $treatments = [];
                    foreach ($doctor->getTreatments() as $treatment) {
                        $treatments[] = [
                            'id' => $treatment->getId(),
                            'name' => $treatment->getName(),
                        ];
                    }

                    return [
                        'firstname' => $doctor->getFirstname(),
                        'lastname' => $doctor->getLastname(),
                        'treatments' => $treatments,
                    ];
                },
                $center->getDoctors()->toArray())
            ];
        }

        return $this->json($list);
    }
}