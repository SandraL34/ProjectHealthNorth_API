<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "payment")]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "card_number", length: 16, nullable: true)]
    private ?string $cardNumber = null;

    #[ORM\Column(name: "expiration_date_month", type: "integer", nullable: true)]
    private ?int $expirationDateMonth = null;

    #[ORM\Column(name: "secret_code", length: 3, nullable: true)]
    private ?string $secretCode = null;

    #[ORM\Column(name: "owner_name", length: 512, nullable: true)]
    private ?string $ownerName = null;

    #[ORM\Column(name: "expiration_date_year", type: "integer")]
    private int $expirationDateYear;

    #[ORM\ManyToOne(targetEntity: Patient::class, inversedBy: "payments")]
    #[ORM\JoinColumn(name: "patient_id", referencedColumnName: "id", nullable: true)]
    private ?Patient $patient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(?string $cardNumber): static
    {
        $this->cardNumber = $cardNumber;
        return $this;
    }

    public function getExpirationDateMonth(): ?int
    {
        return $this->expirationDateMonth;
    }

    public function setExpirationDateMonth(?int $expirationDateMonth): static
    {
        $this->expirationDateMonth = $expirationDateMonth;
        return $this;
    }

    public function getSecretCode(): ?string
    {
        return $this->secretCode;
    }

    public function setSecretCode(?string $secretCode): static
    {
        $this->secretCode = $secretCode;
        return $this;
    }

    public function getOwnerName(): ?string
    {
        return $this->ownerName;
    }

    public function setOwnerName(?string $ownerName): static
    {
        $this->ownerName = $ownerName;
        return $this;
    }

    public function getExpirationDateYear(): int
    {
        return $this->expirationDateYear;
    }

    public function setExpirationDateYear(int $expirationDateYear): static
    {
        $this->expirationDateYear = $expirationDateYear;
        return $this;
    }

    public function getPatient(): ?Patient 
    { 
        return $this->patient; 
    }

    public function setPatient(?Patient $patient): static 
    { 
        $this->patient = $patient; 
        return $this; 
    }
}