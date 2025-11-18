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

    #[ORM\ManyToOne(targetEntity: Doctor::class, inversedBy: "medicines")]
    #[ORM\JoinColumn(name: "attending_physician_id", referencedColumnName: "id", nullable: true)]
    private ?Doctor $attendingPhysician = null;

    #[ORM\ManyToOne(targetEntity: Patient::class, inversedBy: "medicines")]
    #[ORM\JoinColumn(name: "patient_id", referencedColumnName: "id", nullable: true)]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(targetEntity: Treatment::class, inversedBy: "medicines")]
    #[ORM\JoinColumn(name: "treatment_id", referencedColumnName: "id", nullable: true)]
    private ?Treatment $treatment = null;

    #[ORM\ManyToOne(targetEntity: Alarm::class, inversedBy: "medicines")]
    #[ORM\JoinColumn(name: "alarm_id", referencedColumnName: "id", nullable: true)]
    private ?Alarm $alarm = null;

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

    public function getPatient(): ?Patient { return $this->patient; }
    public function setPatient(?Patient $patient): static { $this->patient = $patient; return $this; }

    public function getTreatment(): ?Treatment { return $this->treatment; }
    public function setTreatment(?Treatment $treatment): static { $this->treatment = $treatment; return $this; }

    public function getAlarm(): ?Alarm { return $this->alarm; }
    public function setAlarm(?Alarm $alarm): static { $this->alarm = $alarm; return $this; }
}
