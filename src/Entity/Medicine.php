<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Doctor;

#[ORM\Entity]
#[ORM\Table(name: "medicine")]
class Medicine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 256, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 256, nullable: true)]
    private ?string $frequency = null;

    #[ORM\ManyToOne(targetEntity: Doctor::class)]
    #[ORM\JoinColumn(
        name: "attending_physician_id",
        referencedColumnName: "id",
        nullable: true,
        onDelete: "SET NULL"
    )]
    private ?Doctor $attendingPhysician = null;

    #[ORM\Column(name: "patient_id", type: "integer", nullable: true)]
    private ?int $patientId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getFrequency(): ?string
    {
        return $this->frequency;
    }

    public function setFrequency(?string $frequency): static
    {
        $this->frequency = $frequency;
        return $this;
    }

    public function getAttendingPhysician(): ?Doctor
    {
        return $this->attendingPhysician;
    }

    public function setAttendingPhysician(?Doctor $doctor): static
    {
        $this->attendingPhysician = $doctor;
        return $this;
    }

    public function getPatientId(): ?int
    {
        return $this->patientId;
    }

    public function setPatientId(?int $patientId): static
    {
        $this->patientId = $patientId;
        return $this;
    }
}
