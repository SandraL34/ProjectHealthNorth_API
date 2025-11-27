<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "appointment")]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 256, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 256, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: "date_time", type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateTime = null;

    #[ORM\ManyToOne(targetEntity: Patient::class, inversedBy: "appointments")]
    #[ORM\JoinColumn(name: "patient_id", referencedColumnName: "id", nullable: true)]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(targetEntity: Doctor::class, inversedBy: "appointments")]
    #[ORM\JoinColumn(name: "doctor_id", referencedColumnName: "id", nullable: true)]
    private ?Doctor $doctor = null;

    #[ORM\ManyToMany(targetEntity: Treatment::class, inversedBy: "appointments")]
    #[ORM\JoinTable(name: "appointment_treatment")]
    private Collection $treatments;

    #[ORM\OneToMany(mappedBy: "appointment", targetEntity: Invoice::class)]
    private Collection $invoices;

    #[ORM\OneToMany(mappedBy: "appointment", targetEntity: Prescription::class)]
    private Collection $prescriptions;

    #[ORM\OneToMany(mappedBy: "appointment", targetEntity: Alarm::class)]
    private Collection $alarms;

    #[ORM\OneToMany(mappedBy: "appointment", targetEntity: AppointmentSlot::class)]
    private Collection $appointmentSlots;

    public function __construct()
    {
        $this->invoices = new ArrayCollection();
        $this->treatments = new ArrayCollection();
        $this->prescriptions = new ArrayCollection();
        $this->alarms = new ArrayCollection();
        $this->appointmentSlots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;
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

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(?\DateTimeInterface $dateTime): static
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    public function getPatient(): ?Patient 
    { 
        return $this->patient; 
    }

    public function setPatient(?Patient $patient): static 
    { 
        $this->patient = $patient; 
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

    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    
    public function addTreatment(Treatment $treatment): static
    {
        if (!$this->treatments->contains($treatment)) {
            $this->treatments->add($treatment);
        }
        return $this;
    }

    public function removeTreatment(Treatment $treatment): static
    {
        $this->treatments->removeElement($treatment);
        return $this;
    }

    public function getPrescriptions(): Collection
    {
        return $this->prescriptions;
    }

    public function getTreatments(): Collection
    {
        return $this->treatments;
    }

    public function getAlarms(): Collection
    {
        return $this->alarms;
    }

        public function getAppointmentSlots(): Collection
    {
        return $this->appointmentSlots;
    }
}