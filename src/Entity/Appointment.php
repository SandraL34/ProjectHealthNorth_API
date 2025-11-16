<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Appointment
 *
 * @ORM\Table(name="appointment")
 * @ORM\Entity
 */
class Appointment
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
     * @ORM\Column(name="title", type="string", length=256, nullable=true)
     */
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=256, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="institution_type", type="string", length=256, nullable=true)
     */
    private $institutionType;

    /**
     * @var string|null
     *
     * @ORM\Column(name="specialty_type", type="string", length=256, nullable=true)
     */
    private $specialtyType;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_time", type="datetime", nullable=true)
     */
    private $dateTime;

    /**
     * @var int|null
     *
     * @ORM\Column(name="option_id", type="integer", nullable=true)
     */
    private $optionId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

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

    public function getInstitutionType(): ?string
    {
        return $this->institutionType;
    }

    public function setInstitutionType(?string $institutionType): static
    {
        $this->institutionType = $institutionType;

        return $this;
    }

    public function getSpecialtyType(): ?string
    {
        return $this->specialtyType;
    }

    public function setSpecialtyType(?string $specialtyType): static
    {
        $this->specialtyType = $specialtyType;

        return $this;
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

    public function getOptionId(): ?int
    {
        return $this->optionId;
    }

    public function setOptionId(?int $optionId): static
    {
        $this->optionId = $optionId;

        return $this;
    }


}
