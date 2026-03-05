<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Doctor;
use App\Entity\AppointmentSlot;
use App\Service\SlotGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Appointment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



class AppointmentSlotController extends AbstractController
{
    private SlotGeneratorService $slotService;

    public function __construct(SlotGeneratorService $slotService)
    {
        $this->slotService = $slotService;
    }

    #[Route('/api/doctor/{id}/slots', name: 'doctor_slots')]
    public function slots(Doctor $doctor, SlotGeneratorService $slotService): JsonResponse
    {
        $slots = $slotService->generateSlotsForDoctor($doctor, 30, 30);
        return new JsonResponse($slots);
    }

    #[Route('/api/all-doctors/slots', name: 'all_doctors_slots')]
    public function allDoctorsSlots(SlotGeneratorService $slotService, ManagerRegistry $doctrine): JsonResponse
    {
        $doctors = $doctrine->getRepository(Doctor::class)->findAll();
        $slots = $slotService->generateSlotsForAllDoctors($doctors, 30, 30);

        $slotRepo = $doctrine->getRepository(AppointmentSlot::class);
        $bookedSlots = $slotRepo->findBy(['isBooked' => true]);

        $slots = $slotService->markBookedSlots($slots, $bookedSlots);

        return new JsonResponse($slots);
    }



    #[Route('/api/appointment/change/{id}', name: 'api_appointment_slot_update', methods: ['PATCH'])]
    public function updateAppointment(int $id, Request $request, ManagerRegistry $doctrine): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $em = $doctrine->getManager();

        $appointment = $doctrine->getRepository(Appointment::class)->find($id);
        if (!$appointment) {
            return $this->json(['error' => 'Appointment not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['error' => 'Invalid JSON'], 400);
        }

        $newDate = $data['date'] ?? null;
        $newTime = $data['time'] ?? null;
        $newEndDate = $data['endDate'] ?? null;
        $newEndTime = $data['endTime'] ?? null;

        if ($newDate) {
            $appointment->setDate(new \DateTime($newDate));
        }
        if ($newTime) {
            $appointment->setTime(new \DateTime($newTime));
        }

        $appointmentSlots = $appointment->getAppointmentSlots()->toArray();
        $slot = $appointmentSlots[0] ?? null;

        if ($slot) {
            if ($newDate) {
                $slot->setStartDate(new \DateTime($newDate));
            }
            if ($newTime) {
                $startTime = new \DateTime($newTime);
                $slot->setStartTime($startTime);
            }
            if ($newEndDate) {
                $slot->setEndDate(new \DateTime($newEndDate));
            }
            if ($newEndTime) {
                $slot->setEndTime(new \DateTime($newEndTime));
            }
        }

        $em->flush();

        return new JsonResponse([
            'success' => true,
            'appointment' => [
                'id' => $appointment->getId(),
                'date' => $appointment->getDate()?->format('Y-m-d'),
                'time' => $appointment->getTime()?->format('H:i:s'),
            ],
            'slot' => $slot ? [
                'id' => $slot->getId(),
                'startDate' => $slot->getStartDate()?->format('Y-m-d'),
                'startTime' => $slot->getStartTime()?->format('H:i:s'),
                'endDate' => $slot->getEndDate()?->format('Y-m-d'),
                'endTime' => $slot->getEndTime()?->format('H:i:s'),
            ] : null
        ], Response::HTTP_OK, [
            'Content-Type' => 'application/json; charset=UTF-8',
        ]);
    }

    #[Route('/api/appointment/results', name: 'api_appointment_results', methods: ['GET'])]
    public function results(Request $request, EntityManagerInterface $em, SlotGeneratorService $slotGenerator): Response
    {
        $weekStart = $request->query->get('week');
        $appointmentId = $request->query->get('appointmentId');
    
        if ($weekStart) {
            $startDate = new \DateTimeImmutable($weekStart);
        } else {
            $today = new \DateTimeImmutable();
            $startDate = $today->modify('monday this week');
        }
        
        $endDate = $startDate->modify('+6 days');

        if ($appointmentId) {
            $appointment = $em->getRepository(Appointment::class)->find($appointmentId);
            if (!$appointment || !$appointment->getDoctor()) {
                return new Response(json_encode([]), 200, ['Content-Type' => 'application/json']);
            }
            $doctors = [$appointment->getDoctor()];
        } else {
            $doctors = $em->getRepository(Doctor::class)->findAll();
        }

        $results = [];

        foreach ($doctors as $doctor) {
            $generatedSlots = $slotGenerator->generateSlotsForDoctor($doctor, 60, 7, $startDate);

            $bookedSlots = $doctor->getAppointmentSlots()->toArray();

            $finalSlots = $slotGenerator->markBookedSlots($generatedSlots, $bookedSlots);

            foreach ($finalSlots as $slot) {
                $appointmentId = null;

                if (!empty($slot['isBooked'])) {
                    $appointmentSlot = $em->getRepository(AppointmentSlot::class)->findOneBy([
                        'doctor' => $doctor,
                        'startDate' => new \DateTimeImmutable($slot['startDate']),
                        'startTime' => new \DateTimeImmutable($slot['startTime']),
                        'isBooked' => true
                    ]);

                    if ($appointmentSlot && $appointmentSlot->getAppointment()) {
                        $appointmentId = $appointmentSlot->getAppointment()->getId();
                    }
                }

            $results[] = [
                'doctorId' => $doctor->getId(),
                'doctor' => [
                    'firstname' => $doctor->getFirstname(),
                    'lastname' => $doctor->getLastname(),
                    'center' => $doctor->getCenter() ? [
                        'id' => $doctor->getCenter()->getId(),
                        'name' => $doctor->getCenter()->getName(),
                        'address' => $doctor->getCenter()->getAddress(),
                    ] : null,
                    'treatments' => array_map(fn($t) => [
                        'id' => $t->getId(),
                        'name' => $t->getName(),
                        'duration' => $t->getDuration(),
                    ], $doctor->getTreatments()->toArray()),
                ],
                'slot' => [
                    'startDate' => $slot['startDate'] ?? null,
                    'startTime' => $slot['startTime'] ?? null,
                    'endDate' => $slot['endDate'] ?? null,
                    'endTime' => $slot['endTime'] ?? null,
                    'isBooked' => $slot['isBooked'] ?? false,
                    'appointmentId' => $appointmentId
                ]
            ];
        }
    }

    try {
        foreach ($results as $item) {
            $test = json_encode($item);
            if ($test === false) {
                error_log("JSON encode failed for doctorId: " . ($item['doctorId'] ?? 'unknown'));
                error_log("Error: " . json_last_error_msg());
                error_log("Item: " . print_r($item, true));
            }
        }
        array_walk_recursive($results, function (&$value) {
            if (is_string($value)) {
                $value = iconv('UTF-8', 'UTF-8//IGNORE', $value);
            }
        });

        $json = json_encode($results, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($json === false) {
            return new Response(
                json_encode(['error' => json_last_error_msg()]),
                500,
                ['Content-Type' => 'application/json']
            );
        }

        return new Response($json, 200, ['Content-Type' => 'application/json; charset=UTF-8']);

    } catch (\Throwable $e) {
        return new JsonResponse(['error' => $e->getMessage()], 500);
    }
}

    #[Route('/api/appointment/cancel/{id}', name: 'api_appointment_cancel', methods: ['DELETE'])]
    public function cancelAppointment(int $id, EntityManagerInterface $em): JsonResponse {
        $appointment = $em->getRepository(Appointment::class)->find($id);
        
        if (!$appointment) {
            return new JsonResponse(['error' => 'Appointment not found'], 404);
        }

        $em->remove($appointment);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}