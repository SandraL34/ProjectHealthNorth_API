<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Medicine
 *
 * @ORM\Table(name="medicine")
 * @ORM\Entity
 */
class Medicine
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=256, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="frequency", type="string", length=256, nullable=true)
     */
    private $frequency;

    /**
     * @var int|null
     *
     * @ORM\Column(name="physician_id", type="integer", nullable=true)
     */
    private $physicianId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="patient_id", type="integer", nullable=true)
     */
    private $patientId;

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

    public function getPhysicianId(): ?int
    {
        return $this->physicianId;
    }

    public function setPhysicianId(?int $physicianId): static
    {
        $this->physicianId = $physicianId;

        return $this;
    }

    public function getPatientId(): ?int
    {
        return $this->patientId;
    }

    public function setPatientId(?int $patientId): static
    {
        $this->patientId = $patientId;

        return $this;
    }


}
