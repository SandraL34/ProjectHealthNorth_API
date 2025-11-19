<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Doctor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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

    #[ORM\ManyToOne(targetEntity: Doctor::class, inversedBy: "treatments")]
    #[ORM\JoinColumn(name: "attending_physician_id", referencedColumnName: "id", nullable: true)]
    private ?Doctor $attendingPhysician = null;

    #[ORM\ManyToOne(targetEntity: Patient::class, inversedBy: "treatments")]
    #[ORM\JoinColumn(name: "patient_id", referencedColumnName: "id", nullable: true)]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(targetEntity: Appointment::class, inversedBy: "treatments")]
    #[ORM\JoinColumn(name: "appointment_id", referencedColumnName: "id", nullable: true)]
    private ?Appointment $appointment = null;

    #[ORM\OneToMany(mappedBy: "treatment", targetEntity: Medicine::class)]
    private Collection $medicines;

    #[ORM\OneToMany(mappedBy: "treatment", targetEntity: Prescription::class)]
    private Collection $prescriptions;

        public function __construct()
    {
        $this->medicines = new ArrayCollection();
        $this->prescriptions = new ArrayCollection();
    }

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

    public function getPatient(): ?Patient { return $this->patient; }
    public function setPatient(?Patient $patient): static { $this->patient = $patient; return $this; }

    public function getMedicine(): Collection
    {
        return $this->medicines;
    }

        public function getPrescriptions(): Collection
    {
        return $this->prescriptions;
    }

    public function getAppointment(): ?Appointment { return $this->appointment; }
    public function setAppointment(?Appointment $appointment): static { $this->appointment = $appointment; return $this; }
}