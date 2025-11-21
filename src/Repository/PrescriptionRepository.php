<?php

namespace App\Repository;

use App\Entity\Prescription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PrescriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prescription::class);
    }

    public function findByPatient($patient)
    {
        return $this->createQueryBuilder('p')
            ->join('p.treatment', 't')
            ->where('t.patient = :patient')
            ->setParameter('patient', $patient)
            ->getQuery()
            ->getResult();
    }
}