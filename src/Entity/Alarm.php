<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "alarm")]
class Alarm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "date_time", type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateTime = null;

    #[ORM\Column(length: 256)]
    private string $frequency;

    #[ORM\Column(length: 256)]
    private string $type;

    #[ORM\Column(length: 256)]
    private string $title;

    #[ORM\Column(length: 256)]
    private string $notification;

    #[ORM\ManyToOne(targetEntity: Appointment::class, inversedBy: "alarms")]
    #[ORM\JoinColumn(name: "appointment_id", referencedColumnName: "id", nullable: true)]
    private ?Appointment $appointment = null;

    #[ORM\ManyToOne(targetEntity: Medicine::class, inversedBy: "alarms")]
    #[ORM\JoinColumn(name: "medicine_id", referencedColumnName: "id", nullable: true)]
    private ?Medicine $medicine = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFrequency(): string
    {
        return $this->frequency;
    }

    public function setFrequency(string $frequency): static
    {
        $this->frequency = $frequency;
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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getNotification(): string
    {
        return $this->notification;
    }

    public function setNotification(string $notification): static
    {
        $this->notification = $notification;
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

    public function getMedicine(): ?Medicine
    {
        return $this->medicine;
    }

    public function setMedicine(?Medicine $medicine): static 
    { 
        $this->medicine = $medicine; 
        return $this; 
    }
}