<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CenterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: CenterRepository::class)]
#[ORM\Table(name: "center")]
class Center
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 256, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 256, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(length: 256, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 256, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 256, nullable: true)]
    private ?string $address = null;

    #[ORM\OneToMany(mappedBy: "center", targetEntity: Doctor::class)]
    private Collection $doctors;

    #[ORM\OneToMany(mappedBy: "center", targetEntity: Appointment::class)]
    private Collection $appointments;

    public function __construct()
    {
        $this->doctors = new ArrayCollection();
        $this->appointments = new ArrayCollection();

    }

    public function getId() 
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function getDoctors(): Collection
    {
        return $this->doctors;
    }
        
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }
}