<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Alarm
 *
 * @ORM\Table(name="alarm")
 * @ORM\Entity
 */
class Alarm
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_time", type="datetime", nullable=true)
     */
    private $dateTime;

    /**
     * @var int|null
     *
     * @ORM\Column(name="medicine_id", type="integer", nullable=true)
     */
    private $medicineId;

    /**
     * @var string
     *
     * @ORM\Column(name="frequency", type="string", length=256, nullable=false)
     */
    private $frequency;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=256, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=256, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="notification", type="string", length=256, nullable=false)
     */
    private $notification;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMedicineId(): ?int
    {
        return $this->medicineId;
    }

    public function setMedicineId(?int $medicineId): static
    {
        $this->medicineId = $medicineId;

        return $this;
    }

    public function getFrequency(): ?string
    {
        return $this->frequency;
    }

    public function setFrequency(string $frequency): static
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getNotification(): ?string
    {
        return $this->notification;
    }

    public function setNotification(string $notification): static
    {
        $this->notification = $notification;

        return $this;
    }


}
