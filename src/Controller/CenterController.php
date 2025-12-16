<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Center;
use App\Repository\CenterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

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
                'type' => $center->getType(),
                'email' => $center->getEmail(),
                'phoneNumber' => $center->getPhoneNumber(),
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

    #[Route('/api/centers/change', name: 'api_centers_change', methods:['PUT'])]
    public function changeCenter(Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine): JsonResponse 
    {
        $data = json_decode($request->getContent(), true);

        $center = $doctrine->getRepository(Center::class)->find($data['id'] ?? null);

        if (!$center) {
            return $this->json(['error' => 'Center not found'], 404);
        }


        
        $fields = ['name', 'type', 'email', 'phoneNumber', 'address', 'latitude', 'longitude'];  
        foreach ($fields as $field) {  
            if (isset($data[$field])) {  
                $setter = 'set' . ucfirst($field);  
                $center->$setter($data[$field]);  
            }  
        } 

        $em->flush();

        return $this->json([
            'id' => $center->getId(),
            'name' => $center->getName(),
            'type' => $center->getType(),
            'email' => $center->getEmail(),
            'phoneNumber' => $center->getPhoneNumber(),
            'address' => $center->getAddress(),
            'latitude' => $center->getLatitude(),
            'longitude' => $center->getLongitude(),
        ]);
    }

    #[Route ('/api/centers/add', name: 'api_centers_add', methods:['POST'])]
    public function addCenter(Request $request, CenterRepository $centerRepo, EntityManagerInterface $em,
    ManagerRegistry $doctrine): JsonResponse {

        $data = json_decode($request->getContent(), true);

        if (empty($data['name']) || empty($data['address'])) {
            return $this->json(['error' => 'Missing fields'], 400);
        }

        if ($centerRepo->findOneBy(['name' => $data['name']])) {
            return $this->json(['error' => 'Center already added'], 409);
        }

        $center = new Center();

        $center->setName($data['name'])
                ->setType($data['type'])
                ->setEmail($data['email'])
                ->setPhoneNumber($data['phoneNumber'])
                ->setAddress($data['address'])
                ->setLatitude($data['latitude'])
                ->setLongitude($data['longitude']);

        $em->persist($center);
        $em->flush();

        return $this->json(['success' => true, 'centerId' => $center->getId()], 201);
    }

    #[Route('/api/centers/delete/{id}', name: 'api_centers_delete', methods: ['DELETE'])]
    public function deleteDoctor(int $id, CenterRepository $centerRepo, EntityManagerInterface $em): JsonResponse {
        $center = $centerRepo->find($id);

        if (!$center) {
            return $this->json(['error' => 'Center not found'], 404);
        }

        $em->remove($center);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}