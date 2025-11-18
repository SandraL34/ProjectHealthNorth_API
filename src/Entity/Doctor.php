<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "doctor")]
class Doctor implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 256, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(type: "string", length: 256, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(type: "string", length: 256, nullable: true)]
    private ?string $specialty = null;

    #[ORM\Column(type: "string", length: 10, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(type: "string", length: 256)]
    private string $email;

    #[ORM\Column(type: "string", length: 256)]
    private string $password;

    #[ORM\OneToMany(mappedBy: "attendingPhysician", targetEntity: Patient::class)]
    private Collection $patients;

    #[ORM\OneToMany(mappedBy: "attendingPhysician", targetEntity: Treatment::class)]
    private Collection $treatments;

    #[ORM\OneToMany(mappedBy: "attendingPhysician", targetEntity: Prescription::class)]
    private Collection $prescriptions;

    #[ORM\OneToMany(mappedBy: "attendingPhysician", targetEntity: Medicine::class)]
    private Collection $medicines;

    public function __construct()
    {
        $this->patients = new ArrayCollection();
        $this->treatments = new ArrayCollection();
        $this->prescriptions = new ArrayCollection();
        $this->medicines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getSpecialty(): ?string
    {
        return $this->specialty;
    }

    public function setSpecialty(?string $specialty): static
    {
        $this->specialty = $specialty;
        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        return ['ROLE_DOCTOR'];
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void {}

    public function getPatients(): Collection
    {
        return $this->patients;
    }

        public function getTreatments(): Collection
    {
        return $this->treatments;
    }

        public function getPrescription(): Collection
    {
        return $this->prescriptions;
    }

        public function getMedicine(): Collection
    {
        return $this->medicines;
    }
}