<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Doctor;
use App\Entity\Center;
use App\Entity\Availability;
use App\Entity\Treatment;
use Doctrine\ORM\EntityManagerInterface;

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

    #[Route('/api/doctors/results', name: 'api_doctors_results', methods:['GET'])]
    public function resultsDoctors(ManagerRegistry $doctrine): JsonResponse
    {
        $doctors = $doctrine->getRepository(Doctor::class)->findAll();

        $grouped = [];

        foreach ($doctors as $doctor) {
            $center = $doctor->getCenter(); 

            $availabilitiesArray = [];
                foreach ($doctor->getAvailabilities() as $availability) {
                    $availabilitiesArray[] = [
                        'id' => $availability->getId(),
                        'dayOfWeek' => $availability->getDayOfWeek(),
                        'startTimeAM' => $availability->getStartTimeAM(),
                        'endTimeAM' => $availability->getEndTimeAM(),
                        'startTimePM' => $availability->getStartTimePM(),
                        'endTimePM' => $availability->getEndTimePM(),
                        'isActive' => $availability->isActive(),
                    ];
                }

                
            $treatmentsArray = [];
                foreach ($doctor->getTreatments() as $treatment) {
                    $treatmentsArray[] = [
                        'id' => $treatment->getId(),
                        'name' => $treatment->getName(),
                        'category' => $treatment->getCategory(),
                        'price' => $treatment->getPrice(),
                        'duration' => $treatment->getDuration(),
                    ];
                }

            $grouped[] = [
                'id' => $doctor->getId(),
                'email' => $doctor->getEmail(),
                'firstname' => $doctor->getFirstname(),
                'lastname' => $doctor->getLastName(),
                'phoneNumber' => $doctor->getPhoneNumber(),
                'center'=> $doctor->getCenter()
                ? [
                    'id' => $center->getId(),
                    'name' => $center->getName(),
                    'address' => $center->getAddress(),
                ] : null,
                'availabilities' => $availabilitiesArray,
                'treatments' => $treatmentsArray,
            ];
        }

        return $this->json($grouped);
    }

    #[Route ('/api/doctors/change', name: 'api_doctors_change', methods:['PUT'])]
    public function changeDoctor(Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine): JsonResponse {

        $data = json_decode($request->getContent(), true);

        $doctor = $doctrine->getRepository(Doctor::class)->find($data['id'] ?? null);

        if (!$doctor) {
            return $this->json(['error' => 'Doctor not found'], 404);
        }


        
        $fields = ['firstname', 'lastname', 'email', 'phoneNumber'];  
        foreach ($fields as $field) {  
            if (isset($data[$field])) {  
                $setter = 'set' . ucfirst($field);  
                $doctor->$setter($data[$field]);  
            }  
        } 

        if (!empty($data['centerId'])) {
            $center = $doctrine->getRepository(Center::class)->find($data['centerId']);
            if ($center) {
                $doctor->setCenter($center);
            }
        }


        if (!empty($data['removedTreatments'])) {
            $repo = $em->getRepository(Treatment::class);
            foreach ($data['removedTreatments'] as $id) {
                if ($t = $repo->find($id)) {
                    $doctor->removeTreatment($t);
                }
            }
        }

        if (!empty($data['newTreatments'])) {
            $repo = $em->getRepository(Treatment::class);
            foreach ($data['newTreatments'] as $id) {
                if ($t = $repo->find($id)) {
                    $doctor->addTreatment($t);
                }
            }
        }


        foreach ($doctor->getAvailabilities() as $old) {
            $em->remove($old);
        }

        if (!empty($data['availabilities'])) {
            foreach ($data['availabilities'] as $a) {
                $av = new Availability();
                $av->setDayOfWeek($a['dayOfWeek']);
                $av->setStartTimeAM(new \DateTime($a['startTimeAM']));
                $av->setEndTimeAM(new \DateTime($a['endTimeAM']));
                $av->setStartTimePM(new \DateTime($a['startTimePM']));
                $av->setEndTimePM(new \DateTime($a['endTimePM']));
                $av->setDoctor($doctor);

                $em->persist($av);
            }
        }

        $em->flush();

        $center = $doctor->getCenter(); 
        $availabilities = $doctor->getAvailabilities();
        $treatments = $doctor->getTreatments();


        return $this->json([
            'id' => $doctor->getId(),
            'email' => $doctor->getEmail(),
            'firstname' => $doctor->getFirstname(),
            'lastname' => $doctor->getLastname(),
            'phoneNumber' => $doctor->getPhoneNumber(),

            'center' => $center ? [
                'id' => $center->getId(),
            ] : null,

            'availabilities' => array_map(fn($a) => [
                'id' => $a->getId(),
                'dayOfWeek' => $a->getDayOfWeek(),
                'startTimeAM' => $a->getStartTimeAM(),
                'endTimeAM' => $a->getEndTimeAM(),
                'startTimePM' => $a->getStartTimePM(),
                'endTimePM' => $a->getEndTimePM(),
                'isActive' => $a->isActive(),
                'doctorId' => $doctor->getId(),
            ], $availabilities->toArray()),

            'treatments' => array_map(fn($t) => [
                'id' => $t->getId(),
            ], $treatments->toArray()),
        ]);
    }
}