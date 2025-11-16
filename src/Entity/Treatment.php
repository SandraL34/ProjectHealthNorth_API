<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Treatment
 *
 * @ORM\Table(name="treatment")
 * @ORM\Entity
 */
class Treatment
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
     * @ORM\Column(name="description", type="string", length=512, nullable=true)
     */
    private $description;

    /**
     * @var int|null
     *
     * @ORM\Column(name="price", type="integer", nullable=true)
     */
    private $price;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="paid", type="boolean", nullable=true)
     */
    private $paid;

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

    /**
     * @var int|null
     *
     * @ORM\Column(name="medicine_id", type="integer", nullable=true)
     */
    private $medicineId;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isPaid(): ?bool
    {
        return $this->paid;
    }

    public function setPaid(?bool $paid): static
    {
        $this->paid = $paid;

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

    public function getMedicineId(): ?int
    {
        return $this->medicineId;
    }

    public function setMedicineId(?int $medicineId): static
    {
        $this->medicineId = $medicineId;

        return $this;
    }


}
