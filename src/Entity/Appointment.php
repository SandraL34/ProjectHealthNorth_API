<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "appointment")]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 256, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 256, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: "institution_type", length: 256, nullable: true)]
    private ?string $institutionType = null;

    #[ORM\Column(name: "specialty_type", length: 256, nullable: true)]
    private ?string $specialtyType = null;

    #[ORM\Column(name: "date_time", type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateTime = null;

    #[ORM\Column(name: "option_id", type: "integer", nullable: true)]
    private ?int $optionId = null;

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