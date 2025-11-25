<?php

namespace App\Service;

use App\Entity\Doctor;
use App\Entity\Availability;
use App\Entity\AvailabilityOverride;
use App\Entity\AppointmentSlot;
use Doctrine\ORM\EntityManagerInterface;

class SlotGeneratorService
{
    private EntityManagerInterface $em;
    private int $slotDuration;

    public function __construct(EntityManagerInterface $em, int $slotDuration = 30)
    {
        $this->em = $em;
        $this->slotDuration = $slotDuration;
    }

    public function generateSlotsForAllDoctors(int $days = 30): array
    {
        $allSlots = [];
        $doctors = $this->em->getRepository(Doctor::class)->findAll();

        foreach ($doctors as $doctor) {
            $allSlots = array_merge($allSlots, $this->generateSlots($doctor, $days));
        }

        $this->em->flush(); // persiste tous les slots créés
        return $allSlots;
    }

    public function generateSlots(Doctor $doctor, int $days = 30): array
    {
        $slots = [];
        $today = new \DateTimeImmutable('today');

        for ($i = 0; $i < $days; $i++) {
            $date = $today->modify("+{$i} days");

            if (!$this->isDoctorAvailableThatDay($doctor, $date)) {
                continue;
            }

            $dailySlots = $this->generateSlotsForDay($doctor, $date);
            $slots = array_merge($slots, $dailySlots);
        }

        $this->em->flush(); // persiste tous les slots créés
        return $slots;
    }

    private function isDoctorAvailableThatDay(Doctor $doctor, \DateTimeImmutable $date): bool
    {
        $dow = (int) $date->format('w');

        $override = $this->em->getRepository(AvailabilityOverride::class)
            ->findOneBy(['doctor' => $doctor, 'date' => $date]);

        if ($override !== null) {
            return $override->isActive();
        }

        return $this->em->getRepository(Availability::class)
            ->findOneBy(['doctor' => $doctor, 'dayOfWeek' => $dow, 'isActive' => true]) !== null;
    }

    private function generateSlotsForDay(Doctor $doctor, \DateTimeImmutable $date): array
    {
        $slots = [];
        $dow = (int) $date->format('w');

        $availability = $this->em->getRepository(Availability::class)
            ->findOneBy(['doctor' => $doctor, 'dayOfWeek' => $dow]);

        if (!$availability) {
            return [];
        }

        $ranges = [
            ['start' => $availability->getStartTimeAM(), 'end' => $availability->getEndTimeAM()],
            ['start' => $availability->getStartTimePM(), 'end' => $availability->getEndTimePM()],
        ];

        foreach ($ranges as $range) {
            if (!$range['start'] || !$range['end']) continue;

            $start = new \DateTimeImmutable($date->format('Y-m-d') . ' ' . $range['start']->format('H:i'));
            $end   = new \DateTimeImmutable($date->format('Y-m-d') . ' ' . $range['end']->format('H:i'));

            while ($start < $end) {
                if (!$this->isSlotBooked($doctor, $start)) {
                    // Crée un objet AppointmentSlot
                    $slot = new AppointmentSlot();
                    $slot->setDoctor($doctor)
                        ->setStartDate($start)
                        ->setEndDate($start)
                        ->setStartTime($start)
                        ->setEndTime($start->modify("+{$this->slotDuration} minutes"))
                        ->setIsBooked(false);

                    $this->em->persist($slot);
                    $slots[] = $slot;
                }

                $start = $start->modify("+{$this->slotDuration} minutes");
            }
        }

        return $slots;
    }

    private function isSlotBooked(Doctor $doctor, \DateTimeImmutable $startDateTime): bool
    {
        return $this->em->getRepository(AppointmentSlot::class)->findOneBy([
            'doctor' => $doctor,
            'startDate' => $startDateTime,
            'startTime' => $startDateTime,
            'isBooked' => true,
        ]) !== null;
    }
}
