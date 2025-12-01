<?php

namespace App\Service;

use App\Entity\Doctor;

class SlotGeneratorService
{
    /**
     *
     * @param Doctor $doctor
     * @param int $slotDuration Minutes
     * @param int $daysAhead Nombre de jours à générer
     * @return array
     */
    public function generateSlotsForDoctor(Doctor $doctor, int $slotDuration = 30, int $daysAhead = 30): array
    {
        $slots = [];
        $today = new \DateTimeImmutable();

        for ($i = 0; $i <= $daysAhead; $i++) {
            $date = $today->modify("+$i days");
            $dayOfWeek = (int) $date->format('N');

            foreach ($doctor->getAvailabilities() as $availability) {
                if (!$availability->isActive() || $availability->getDayOfWeek() !== $dayOfWeek) continue;

                $start = \DateTimeImmutable::createFromFormat('H:i:s', $availability->getStartTimeAM()->format('H:i:s'));
                $end = \DateTimeImmutable::createFromFormat('H:i:s', $availability->getEndTimeAM()->format('H:i:s'));
                for ($slotStart = $start; $slotStart < $end; $slotStart = $slotStart->modify("+$slotDuration minutes")) {
                    $slotEnd = $slotStart->modify("+$slotDuration minutes");
                    $slots[] = [
                        'doctorId' => $doctor->getId(),
                        'startDate' => $date->format('Y-m-d'),
                        'startTime' => $slotStart->format('H:i:s'),
                        'endDate' => $date->format('Y-m-d'),
                        'endTime' => $slotEnd->format('H:i:s'),
                        'isBooked' => false,
                        'appointment' => null,
                    ];
                }

                $start = \DateTimeImmutable::createFromFormat('H:i:s', $availability->getStartTimePM()->format('H:i:s'));
                $end = \DateTimeImmutable::createFromFormat('H:i:s', $availability->getEndTimePM()->format('H:i:s'));
                for ($slotStart = $start; $slotStart < $end; $slotStart = $slotStart->modify("+$slotDuration minutes")) {
                    $slotEnd = $slotStart->modify("+$slotDuration minutes");
                    $slots[] = [
                        'doctorId' => $doctor->getId(),
                        'startDate' => $date->format('Y-m-d'),
                        'startTime' => $slotStart->format('H:i:s'),
                        'endDate' => $date->format('Y-m-d'),
                        'endTime' => $slotEnd->format('H:i:s'),
                        'isBooked' => false,
                        'appointment' => null,
                    ];
                }
            }
        }

        return $slots;
    }

    public function generateSlotsForAllDoctors(array $doctors, int $slotDuration = 30, int $daysAhead = 30): array
    {
        $allSlots = [];
        foreach ($doctors as $doctor) {
            $allSlots = array_merge($allSlots, $this->generateSlotsForDoctor($doctor, $slotDuration, $daysAhead));
        }
        return $allSlots;
    }

    public function markBookedSlots(array $slots, array $bookedSlots): array
    {
        foreach ($slots as &$slot) {
            foreach ($bookedSlots as $booked) {
                if (
                    $slot['doctorId'] === $booked->getDoctor()?->getId() &&
                    $slot['startDate'] === $booked->getStartDate()?->format('Y-m-d') &&
                    $slot['startTime'] === $booked->getStartTime()?->format('H:i:s')
                ) {
                    $slot['isBooked'] = true;
                    $appointment = $booked->getAppointment();
                    if ($appointment) {
                        $slot['appointment'] = [
                            'id' => $appointment->getId(),
                            'title' => $appointment->getTitle(),
                        ];
                    }
                }
            }
        }
        return $slots;
    }
}