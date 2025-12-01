<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AvailabilityRepository;

#[ORM\Entity(repositoryClass: AvailabilityRepository::class)]
#[ORM\Table(name: "availability")]
class Availability
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "smallint")]
    private ?int $dayOfWeek = null;

    #[ORM\Column(type: "time")]
    private ?\DateTimeInterface $startTimeAM = null;

    #[ORM\Column(type: "time")]
    private ?\DateTimeInterface $endTimeAM = null;

    #[ORM\Column(type: "time")]
    private ?\DateTimeInterface $startTimePM = null;

    #[ORM\Column(type: "time")]
    private ?\DateTimeInterface $endTimePM = null;

    #[ORM\Column(type: "boolean")]
    private bool $isActive = true;

    #[ORM\ManyToOne(targetEntity: Doctor::class, inversedBy: "availabilities")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Doctor $doctor = null;

    // --- Getters & Setters ---

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDayOfWeek(): ?int
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(int $dayOfWeek): static
    {
        $this->dayOfWeek = $dayOfWeek;
        return $this;
    }

    public function getStartTimeAM(): ?\DateTimeInterface
    {
        return $this->startTimeAM;
    }

    public function setStartTimeAM(\DateTimeInterface $startTimeAM): static
    {
        $this->startTimeAM = $startTimeAM;
        return $this;
    }

    public function getEndTimeAM(): ?\DateTimeInterface
    {
        return $this->endTimeAM;
    }

    public function setEndTimeAM(\DateTimeInterface $endTimeAM): static
    {
        $this->endTimeAM = $endTimeAM;
        return $this;
    }

    public function getStartTimePM(): ?\DateTimeInterface
    {
        return $this->startTimePM;
    }

    public function setStartTimePM(\DateTimeInterface $startTimePM): static
    {
        $this->startTimePM = $startTimePM;
        return $this;
    }

    public function getEndTimePM(): ?\DateTimeInterface
    {
        return $this->endTimePM;
    }

    public function setEndTimePM(\DateTimeInterface $endTimePM): static
    {
        $this->endTimePM = $endTimePM;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }
}