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
                    if ($appointment) {
                        $slot['appointment'] = [
                            'id' => $appointment->getId(),
                            'title' => $appointment->getTitle(),
                            'date' => $appointment->getDate()?->format('Y-m-d'),
                            'time' => $appointment->getTime()?->format('H:i:s'),
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