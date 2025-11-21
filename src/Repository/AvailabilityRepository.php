<?php

namespace App\Repository;

use App\Entity\Availability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AvailabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Availability::class);
    }

    public function findActiveByDoctorAndDay(int $doctorId, int $dayOfWeek)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.doctor = :doctorId')
            ->andWhere('a.dayOfWeek = :dayOfWeek')
            ->andWhere('a.isActive = true')
            ->setParameter('doctorId', $doctorId)
            ->setParameter('dayOfWeek', $dayOfWeek)
            ->orderBy('a.startTime', 'ASC')
            ->getQuery()
            ->getResult();
    }
}