<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PrescriptionRepository;

#[ORM\Entity(repositoryClass: PrescriptionRepository::class)]
#[ORM\Table(name: "prescription")]

class Prescription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "report", length: 1024, nullable: true)]
    private ?string $report = null;

    #[ORM\Column(name: "prescription_details", length: 1024, nullable: true)]
    private ?string $prescriptionDetails = null;

    #[ORM\ManyToOne(targetEntity: Doctor::class, inversedBy: "prescriptions")]
    #[ORM\JoinColumn(name: "doctor_id", referencedColumnName: "id", nullable: true)]
    private ?Doctor $doctor = null;

    #[ORM\ManyToOne(targetEntity: Treatment::class, inversedBy: "prescriptions")]
    #[ORM\JoinColumn(name: "treatment_id", referencedColumnName: "id", nullable: true)]
    private ?Treatment $treatment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReport(): ?string
    {
        return $this->report;
    }

    public function setReport(?string $report): static
    {
        $this->report = $report;
        return $this;
    }

    public function getPrescriptionDetails(): ?string
    {
        return $this->prescriptionDetails;
    }

    public function setPrescriptionDetails(?string $prescriptionDetails): static
    {
        $this->prescriptionDetails = $prescriptionDetails;
        return $this;
    }

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(?Doctor $doctor): static
    {
        $this->doctor = $doctor;
        return $this;
    }

        public function getTreatment(): ?Treatment
    {
        return $this->treatment;
    }

    public function setTreatment(?Treatment $treatment): static
    {
        $this->treatment = $treatment;
        return $this;
    }

}