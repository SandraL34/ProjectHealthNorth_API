<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "document")]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 256)]
    private string $fileName;

    #[ORM\Column(type: "string", length: 256)]
    private string $displayName;

    #[ORM\Column(type: "string", length: 128)]
    private string $type;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $dateUpload;

    #[ORM\ManyToOne(targetEntity: Patient::class, inversedBy: "documents")]
    #[ORM\JoinColumn(name: "patient_id", referencedColumnName: "id", nullable: true)]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(targetEntity: Appointment::class, inversedBy: "documents")]
    #[ORM\JoinColumn(name: "appointment_id", referencedColumnName: "id", nullable: true)]
    private ?Appointment $appointment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): static
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): static
    {
        $this->displayName = $displayName;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getDateUpload(): ?\DateTimeImmutable
    {
        return $this->dateUpload;
    }

    public function setDateUpload(\DateTimeImmutable $dateUpload): static
    {
        $this->dateUpload = $dateUpload;
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

    public function getAppointment(): ?Appointment 
    { 
        return $this->appointment; 
    }

    public function setAppointment(?Appointment $appointment): static 
    { 
        $this->appointment = $appointment; 
        return $this; 
    }
}