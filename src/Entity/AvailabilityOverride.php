<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name:"availability_override")]
class AvailabilityOverride {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type:"date")]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type:"boolean")]
    private bool $isActive = false;

    #[ORM\ManyToOne(targetEntity: Doctor::class, inversedBy: "availabilitiesOverride")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Doctor $doctor = null;

    public function getId(): ?int 
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function isActive(): ?bool 
    {
        return $this->isActive;
    }

    public function setActive(bool $isActive): static
    {
        $this->isActive = $isActive;
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

}