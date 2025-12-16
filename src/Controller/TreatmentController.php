<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Treatment;
use App\Repository\TreatmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

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

    #[Route('/api/treatments/change', name: 'api_treatments_change', methods:['PUT'])]
    public function changeTreatment(Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine): JsonResponse 
    {
        $data = json_decode($request->getContent(), true);

        $treatment = $doctrine->getRepository(Treatment::class)->find($data['id'] ?? null);

        if (!$treatment) {
            return $this->json(['error' => 'Treatment not found'], 404);
        }


        
        $fields = ['name', 'category', 'duration', 'price'];  
        foreach ($fields as $field) {  
            if (isset($data[$field])) {  
                $setter = 'set' . ucfirst($field);  
                $treatment->$setter($data[$field]);  
            }  
        } 

        $em->flush();

        return $this->json([
            'id' => $treatment->getId(),
            'name' => $treatment->getName(),
            'category' => $treatment->getCategory(),
            'duration' => $treatment->getDuration(),
            'price' => $treatment->getPrice(),
        ]);
    }

        #[Route ('/api/treatments/add', name: 'api_treatments_add', methods:['POST'])]
    public function addTreatment(Request $request, TreatmentRepository $treatmentRepo, EntityManagerInterface $em,
    ManagerRegistry $doctrine): JsonResponse {

        $data = json_decode($request->getContent(), true);

        if (empty($data['name']) || empty($data['category'])) {
            return $this->json(['error' => 'Missing fields'], 400);
        }

        if ($treatmentRepo->findOneBy(['name' => $data['name']])) {
            return $this->json(['error' => 'Treatment already added'], 409);
        }

        $treatment = new Treatment();

        $treatment->setName($data['name'])
                ->setCategory($data['category'])
                ->setDuration($data['duration'])
                ->setPrice($data['price']);

        $em->flush();

        return $this->json(['success' => true, 'treatmentId' => $treatment->getId()], 201);
    }

    #[Route('/api/treatments/delete/{id}', name: 'api_treatments_delete', methods: ['DELETE'])]
    public function deleteDoctor(int $id, TreatmentRepository $treatmentRepo, EntityManagerInterface $em): JsonResponse {
        $treatment = $treatmentRepo->find($id);

        if (!$treatment) {
            return $this->json(['error' => 'Treatment not found'], 404);
        }

        $em->remove($treatment);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}