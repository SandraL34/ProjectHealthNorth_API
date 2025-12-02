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
        return $this->json($slots);
    }

    #[Route('/api/all-doctors/slots', name: 'all_doctors_slots')]
    public function allDoctorsSlots(SlotGeneratorService $slotService, ManagerRegistry $doctrine): JsonResponse
    {
        $doctors = $doctrine->getRepository(Doctor::class)->findAll();
        $slots = $slotService->generateSlotsForAllDoctors($doctors, 30, 30);

        $slotRepo = $doctrine->getRepository(AppointmentSlot::class);
        $bookedSlots = $slotRepo->findBy(['isBooked' => true]);

        $slots = $slotService->markBookedSlots($slots, $bookedSlots);

        return $this->json($slots);
    }

    #[Route('/api/appointment/change', name: 'api_appointment_change', methods: ['GET'])]
    public function appointmentChange(ManagerRegistry $doctrine, SlotGeneratorService $slotService): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $doctors = $doctrine->getRepository(Doctor::class)->findAll();
        $slots = $slotService->generateSlotsForAllDoctors($doctors, 30);

        $slotRepo = $doctrine->getRepository(AppointmentSlot::class);
        $bookedSlots = $slotRepo->findBy(['isBooked' => true]);

        foreach ($slots as &$slot) {
            foreach ($bookedSlots as $booked) {
                if ($slot['doctorId'] === $booked->getDoctor()?->getId() &&
                    $slot['startDate'] === $booked->getStartDate()?->format('Y-m-d') &&
                    $slot['startTime'] === $booked->getStartTime()?->format('H:i:s')
                ) {
                    $appointment = $booked->getAppointment();
                    $appointmentSlots = $appointment->getAppointmentSlots()->toArray();
                    $appointmentSlotIds = array_map(fn($s) => $s->getId(), $appointmentSlots);
                    if ($appointment) {
                        $slot['appointment'] = [
                            'id' => $appointment->getId(),
                            'title' => $appointment->getTitle(),
                            'date' => $appointment->getDate()?->format('Y-m-d'),
                            'time' => $appointment->getTime()?->format('H:i:s'),
                            'appointmentSlotIds' => $appointmentSlotIds,
                            'treatments' => array_map(fn($t) => [
                                'id' => $t->getId(),
                                'name' => $t->getName(),
                                'duration' => $t->getDuration()
                            ], $appointment->getTreatments()->toArray())
                        ];
                        $slot['isBooked'] = true;
                    }
                }
            }
        }

        $results = [];
        foreach ($slots as $slot) {
            $doctor = $doctrine->getRepository(Doctor::class)->find($slot['doctorId']);
            if (!$doctor) continue;

            $center = $doctor->getCenter();
            $doctorTreatments = $doctor->getTreatments()->toArray();

            $results[] = [
                'doctor' => [
                    'id' => $doctor->getId(),
                    'firstname' => $doctor->getFirstname(),
                    'lastname' => $doctor->getLastname(),
                    'center' => $center ? [
                        'id' => $center->getId(),
                        'name' => $center->getName(),
                        'address' => $center->getAddress(),
                    ] : null,
                    'treatments' => array_map(fn($t) => [
                        'id' => $t->getId(),
                        'name' => $t->getName(),
                        'duration' => $t->getDuration(),
                    ], $doctorTreatments),
                ],
                'slot' => $slot,
            ];
        }

        return $this->json($results);
    }

    #[Route('/api/appointment/change/{id}', name: 'api_appointment_update', methods: ['PATCH'])]
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

        return $this->json([
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
        ]);
    }

    #[Route('/api/appointment/results', name: 'api_appointment_results', methods: ['GET'])]
    public function results(EntityManagerInterface $em, SlotGeneratorService $slotGenerator): JsonResponse
    {
        $doctors = $em->getRepository(Doctor::class)->findAll();
        $results = [];

        foreach ($doctors as $doctor) {

            $generatedSlots = $slotGenerator->generateSlotsForDoctor($doctor);

            $bookedSlots = $doctor->getAppointmentSlots()->toArray();

            $finalSlots = $slotGenerator->markBookedSlots($generatedSlots, $bookedSlots);

            foreach ($finalSlots as $slot) {
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
                    'slot' => $slot
                ];
            }
        }

        return $this->json($results);
    }
}