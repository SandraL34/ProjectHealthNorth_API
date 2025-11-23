<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PrescriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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

    #[ORM\ManyToOne(targetEntity: Appointment::class, inversedBy: "prescriptions")]
    #[ORM\JoinColumn(name: "appointment_id", referencedColumnName: "id", nullable: true)]
    private ?Appointment $appointment = null;

    #[ORM\OneToMany(mappedBy: "prescriptions", targetEntity: Medicine::class)]
    private Collection $medicines;

    public function __construct()
    {
        $this->medicines = new ArrayCollection();
    }

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

    public function getAppointment(): ?Appointment
    {
        return $this->appointment;
    }

    public function setAppointment(?Appointment $appointment): static
    {
        $this->appointment = $appointment;
        return $this;
    }

    public function getMedicines(): Collection
    {
        return $this->medicines;
    }
}