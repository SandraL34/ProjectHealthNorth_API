<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Doctor;

#[ORM\Entity]
#[ORM\Table(name: "treatment")]
class Treatment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 256, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $price = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $paid = null;

    #[ORM\ManyToOne(targetEntity: Doctor::class)]
    #[ORM\JoinColumn(
        name: "attending_physician_id",
        referencedColumnName: "id",
        nullable: true,
        onDelete: "SET NULL"
    )]
    private ?Doctor $attendingPhysician = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $patientId = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $medicineId = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function isPaid(): ?bool
    {
        return $this->paid;
    }

    public function setPaid(?bool $paid): static
    {
        $this->paid = $paid;
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

    public function getMedicineId(): ?int
    {
        return $this->medicineId;
    }

    public function setMedicineId(?int $medicineId): static
    {
        $this->medicineId = $medicineId;
        return $this;
    }
}