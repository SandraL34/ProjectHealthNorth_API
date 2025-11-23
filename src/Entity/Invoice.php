<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "invoice")]

class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "boolean", length: 256, nullable: true)]
    private ?bool $isPaid = false;

    #[ORM\ManyToOne(targetEntity: Appointment::class, inversedBy: "invoices")]
    #[ORM\JoinColumn(name: "appointment_id", referencedColumnName: "id", nullable: true)]
    private ?Appointment $appointment = null;

    public function getId(): ?int
    {
        return $this-> id;
    }

        public function getIsPaid(): ?bool
    {
        return $this-> isPaid;
    }

    public function setIsPaid(bool $isPaid): static
    {
        $this->isPaid = $isPaid;
        return $this;
    }

    public function getAppointment(): ?Appointment
    {
        return $this-> appointment;
    }

    public function setAppointment(?Appointment $appointment): static 
    {
        $this->appointment = $appointment;
        return $this;
    }
}