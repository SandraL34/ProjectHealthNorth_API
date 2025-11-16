<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Option
 *
 * @ORM\Table(name="option")
 * @ORM\Entity
 */
class Option
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
     * @ORM\Column(name="communication_form", type="string", length=256, nullable=true)
     */
    private $communicationForm;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="private_room", type="boolean", nullable=true)
     */
    private $privateRoom;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="television", type="boolean", nullable=true)
     */
    private $television;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="wifi", type="boolean", nullable=true)
     */
    private $wifi;

    /**
     * @var string|null
     *
     * @ORM\Column(name="diet", type="string", length=256, nullable=true)
     */
    private $diet;

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


}
