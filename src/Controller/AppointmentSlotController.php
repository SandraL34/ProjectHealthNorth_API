<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Doctor;
use App\Entity\AppointmentSlot;
use App\Service\SlotGeneratorService;

class AppointmentSlotController extends AbstractController {

    #[Route('/doctor/{id}/slots', name: 'doctor_slots')]
    public function slots(Doctor $doctor, SlotGeneratorService $slotService): JsonResponse
    {
        $slots = $slotService->generateSlots($doctor, 30);

        return $this->json($slots);
    }

    #[Route('/all-doctors/slots', name: 'all_doctors_slots')]
    public function allDoctorsSlots(SlotGeneratorService $slotService): JsonResponse
    {
        $slots = $slotService->generateSlotsForAllDoctors(30);
        return $this->json($slots);
    }

    
    #[Route('/api/appointment/results', name: 'api_appointment_results', methods: ['GET'])]
    public function appointmentResults(ManagerRegistry $doctrine, SlotGeneratorService $slotService): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $em = $doctrine->getManager();
        $slotRepo = $doctrine->getRepository(AppointmentSlot::class);

        $appointmentSlots = $slotRepo->findAll();

        if (empty($appointmentSlots)) {
            $appointmentSlots = $slotService->generateSlotsForAllDoctors(30);

            foreach ($appointmentSlots as $slot) {
                $em->persist($slot);
            }
            $em->flush();

            $appointmentSlots = $slotRepo->findAll();
        }

        $results = [];

        foreach ($appointmentSlots as $slot) {

            $doctor = $slot->getDoctor();
            if (!$doctor) continue;

            $center = $doctor->getCenter();

            $treatmentsArray = [];
            foreach ($doctor->getTreatments() as $treatment) {
                $treatmentsArray[] = [
                    'id' => $treatment->getId(),
                    'name' => $treatment->getName(),
                    'duration' => $treatment->getDuration(),
                ];
            }

            $results[] = [
                'slot' => [
                    'startDate' => $slot->getStartDate()?->format('Y-m-d'),
                    'startTime' => $slot->getStartTime()?->format('H:i'),
                    'endDate' => $slot->getEndDate()?->format('Y-m-d'),
                    'endTime' => $slot->getEndTime()?->format('H:i'),
                    'isBooked' => $slot->isBooked(),
                ],
                'doctor' => [
                    'firstname' => $doctor->getFirstname(),
                    'lastname' => $doctor->getLastname(),
                    'center' => $center ? [
                        'id' => $center->getId(),
                        'name' => $center->getName(),
                        'address' => $center->getAddress(),
                    ] : null,
                    'treatments' => $treatmentsArray,
                ],
            ];
        }

        return $this->json($results);
    }
}