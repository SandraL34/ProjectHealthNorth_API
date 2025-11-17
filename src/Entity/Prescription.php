<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
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
}