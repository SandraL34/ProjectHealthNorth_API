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

    #[ORM\ManyToOne(targetEntity: Patient::class, inversedBy: "alarms")]
    #[ORM\JoinColumn(name: "patient_id", referencedColumnName: "id", nullable: true)]
    private ?Patient $patient = null;

    #[ORM\OneToMany(mappedBy: "alarm", targetEntity: Medicine::class)]
    private Collection $medicines;

    public function __construct()
    {
        $this->medicines = new ArrayCollection();
    }

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

    public function getPatient(): ?Patient { return $this->patient; }
    public function setPatient(?Patient $patient): static { $this->patient = $patient; return $this; }

    public function getMedicines(): Collection
    {
        return $this->medicines;
    }
}