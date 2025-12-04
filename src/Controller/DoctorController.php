<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Doctor;

class DoctorController extends AbstractController
{
    #[Route('/api/doctors/search', name: 'api_doctors_search', methods: ['GET'])]
    public function searchDoctors(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $query = $request->query->get('query', '');
    /** @var \App\Repository\DoctorRepository $repo */
    $repo = $doctrine->getRepository(Doctor::class);

    $doctors = $repo->createQueryBuilder('d')
        ->where('d.firstname LIKE :query OR d.lastname LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult();
        
        $data = array_map(fn($d) => [
            'id' => $d->getId(),
            'firstname' => $d->getFirstname(),
            'lastname' => $d->getLastname()
        ], $doctors);

        return $this->json($data);
    }

    #[Route('/api/doctors/list', name: 'api_doctors_list', methods: ['GET'])]
    public function listDoctors(ManagerRegistry $doctrine): JsonResponse
    {
        $doctors = $doctrine->getRepository(Doctor::class)->findAll();

        $grouped = [];

        foreach ($doctors as $doctor) {
            $grouped[] = [
                'id' => $doctor->getId(),
                'firstname' => $doctor->getFirstname(),
                'lastname' => $doctor->getLastName(),
            ];
        }

        return $this->json($grouped);
    }
}