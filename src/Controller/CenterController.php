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
    public function searchCenters(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $query = $request->query->get('query', '');
    /** @var \App\Repository\CenterRepository $repo */
    $repo = $doctrine->getRepository(Center::class);

    $centers = $repo->createQueryBuilder('d')
        ->where('d.name LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult();
        
        $data = array_map(fn($d) => [
            'id' => $d->getId(),
            'name' => $d->getName()
        ], $centers);

        return $this->json($data);
    }
}