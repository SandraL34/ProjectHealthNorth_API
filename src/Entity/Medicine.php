<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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

    #[ORM\ManyToOne(targetEntity: Prescription::class, inversedBy: "medicines")]
    #[ORM\JoinColumn(name: "prescription_id", referencedColumnName: "id", nullable: true)]
    private ?Prescription $prescription = null;

    #[ORM\OneToMany(mappedBy: "medicine", targetEntity: Alarm::class)]
    private Collection $alarms;

    public function __construct()
    {
        $this->alarms = new ArrayCollection();
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

    public function getFrequency(): ?string
    {
        return $this->frequency;
    }

    public function setFrequency(?string $frequency): static
    {
        $this->frequency = $frequency;
        return $this;
    }

    public function getPrescription(): ?Prescription
    {
        return $this->prescription;
    }

    public function setPrescription(?Prescription $prescription): static
    {
        $this->prescription = $prescription;
        return $this;
    }

    public function getAlarms(): Collection
    {
        return $this->alarms;
    }
}
