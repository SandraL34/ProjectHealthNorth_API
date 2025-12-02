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

    public function markBookedSlots(array $generatedSlots, array $bookedSlots): array
    {
        $booked = [];
        foreach ($bookedSlots as $b) {
            $booked[] = [
                'doctorId' => $b->getDoctor()?->getId(),
                'startDate' => $b->getStartDate()?->format('Y-m-d'),
                'startTime' => $b->getStartTime()?->format('H:i:s'),
                'endDate' => $b->getEndDate()?->format('Y-m-d'),
                'endTime' => $b->getEndTime()?->format('H:i:s'),
                'appointmentId' => $b->getAppointment()?->getId(),
                'appointmentTitle' => $b->getAppointment()?->getTitle(),
            ];
        }

        foreach ($generatedSlots as &$slot) {
            $slot['isBooked'] = $slot['isBooked'] ?? false;
            $slot['appointment'] = $slot['appointment'] ?? null;

            $slotStart = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $slot['startDate'] . ' ' . $slot['startTime']);
            if (!empty($slot['endDate']) && !empty($slot['endTime'])) {
                $slotEnd = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $slot['endDate'] . ' ' . $slot['endTime']);
            } else {
                $slotEnd = $slotStart->modify('+30 minutes');
            }

            foreach ($booked as $b) {
                if ($slot['doctorId'] !== $b['doctorId']) continue;

                $bookedStart = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $b['startDate'] . ' ' . $b['startTime']);
                $bookedEnd = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $b['endDate'] . ' ' . $b['endTime']);

                if (!$slotStart || !$slotEnd || !$bookedStart || !$bookedEnd) continue;

                if ($slotStart < $bookedEnd && $slotEnd > $bookedStart) {
                    $slot['isBooked'] = true;
                    $slot['appointment'] = [
                        'id' => $b['appointmentId'],
                        'title' => $b['appointmentTitle'] ?? null,
                    ];
                    $slot['bookedStartDate'] = $b['startDate'];
                    $slot['bookedStartTime'] = $b['startTime'];
                    $slot['bookedEndDate'] = $b['endDate'];
                    $slot['bookedEndTime'] = $b['endTime'];

                    break;
                }
            }
        }
        unset($slot);

        return $generatedSlots;
    }
}