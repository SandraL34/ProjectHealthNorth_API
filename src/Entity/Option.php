<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "option")]
class Option
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "communication_form", length: 256, nullable: true)]
    private ?string $communicationForm = null;

    #[ORM\Column(name: "private_room", type: "boolean", nullable: true)]
    private ?bool $privateRoom = null;

    #[ORM\Column(name: "television", type: "boolean", nullable: true)]
    private ?bool $television = null;

    #[ORM\Column(name: "wifi", type: "boolean", nullable: true)]
    private ?bool $wifi = null;

    #[ORM\Column(name: "diet", length: 256, nullable: true)]
    private ?string $diet = null;

    #[ORM\OneToMany(mappedBy: "option", targetEntity: Patient::class)]
    private Collection $patients;

    #[ORM\OneToMany(mappedBy: "option", targetEntity: Appointment::class)]
    private Collection $appointments;


    public function __construct()
    {
        $this->patients = new ArrayCollection();
        $this->appointments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommunicationForm(): ?string
    {
        return $this->communicationForm;
    }

    public function setCommunicationForm(?string $communicationForm): static
    {
        $this->communicationForm = $communicationForm;
        return $this;
    }

    public function isPrivateRoom(): ?bool
    {
        return $this->privateRoom;
    }

    public function setPrivateRoom(?bool $privateRoom): static
    {
        $this->privateRoom = $privateRoom;
        return $this;
    }

    public function isTelevision(): ?bool
    {
        return $this->television;
    }

    public function setTelevision(?bool $television): static
    {
        $this->television = $television;
        return $this;
    }

    public function isWifi(): ?bool
    {
        return $this->wifi;
    }

    public function setWifi(?bool $wifi): static
    {
        $this->wifi = $wifi;
        return $this;
    }

    public function getDiet(): ?string
    {
        return $this->diet;
    }

    public function setDiet(?string $diet): static
    {
        $this->diet = $diet;
        return $this;
    }

    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function getAppointments(): Collection
    {
        return $this->appointments;
    }
}