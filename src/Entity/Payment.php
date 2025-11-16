<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 *
 * @ORM\Table(name="payment")
 * @ORM\Entity
 */
class Payment
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
     * @ORM\Column(name="card_number", type="string", length=16, nullable=true)
     */
    private $cardNumber;

    /**
     * @var int|null
     *
     * @ORM\Column(name="expiration_date_month", type="integer", nullable=true)
     */
    private $expirationDateMonth;

    /**
     * @var string|null
     *
     * @ORM\Column(name="secret_code", type="string", length=3, nullable=true)
     */
    private $secretCode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="owner_name", type="string", length=512, nullable=true)
     */
    private $ownerName;

    /**
     * @var int
     *
     * @ORM\Column(name="expiration_date_year", type="integer", nullable=false)
     */
    private $expirationDateYear;

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

    public function getExpirationDateYear(): ?int
    {
        return $this->expirationDateYear;
    }

    public function setExpirationDateYear(int $expirationDateYear): static
    {
        $this->expirationDateYear = $expirationDateYear;

        return $this;
    }
}
