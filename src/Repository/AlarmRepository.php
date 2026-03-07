<?php

namespace App\Repository;

use App\Entity\Alarm;
use App\Entity\Appointment;
use App\Entity\Medicine;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AlarmRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;


    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Alarm::class);
        $this->em = $em;
    }


    public function createAlarm(
        ?\DateTimeInterface $dateTime,
        string $frequency,
        string $type,
        string $title,
        string $notification,
        ?Appointment $appointment = null,
        ?Medicine $medicine = null
    ): Alarm {

        $alarm = new Alarm();

        $alarm->setDateTime($dateTime);
        $alarm->setFrequency($frequency);
        $alarm->setType($type);
        $alarm->setTitle($title);
        $alarm->setNotification($notification);
        $alarm->setAppointment($appointment);
        $alarm->setMedicine($medicine);

        $this->em->persist($alarm);
        $this->em->flush();

        return $alarm;
    }


    public function updateAlarm(
        Alarm $alarm,
        ?\DateTimeInterface $dateTime,
        string $frequency,
        string $type,
        string $title,
        string $notification
    ): Alarm {

        $alarm->setDateTime($dateTime);
        $alarm->setFrequency($frequency);
        $alarm->setType($type);
        $alarm->setTitle($title);
        $alarm->setNotification($notification);

        $this->em->flush();

        return $alarm;
    }

    
    public function deleteAlarm(Alarm $alarm): void
    {
        $this->em->remove($alarm);
        $this->em->flush();
    }

    public function findByPatient($patient)
    {
        return $this->createQueryBuilder('a')
            ->where('a.patient = :patient')
            ->setParameter('patient', $patient)
            ->getQuery()
            ->getResult();
    }
}