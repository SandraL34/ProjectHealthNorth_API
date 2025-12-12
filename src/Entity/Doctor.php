<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\DoctorRepository;

#[ORM\Entity(repositoryClass: DoctorRepository::class)]
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

    #[ORM\Column(type: "string", length: 10, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(type: "string", length: 256)]
    private string $email;

    #[ORM\Column(type: "string", length: 256)]
    private string $password;

    #[ORM\ManyToOne(targetEntity: Center::class, inversedBy: "doctors")]
    #[ORM\JoinColumn(name: "center_id", referencedColumnName: "id", nullable: true)]
    private ?Center $center = null;

    #[ORM\OneToMany(mappedBy: "doctor", targetEntity: Patient::class)]
    private Collection $patients;

    #[ORM\ManyToMany(targetEntity: Treatment::class, inversedBy: "doctors")]
    #[ORM\JoinTable(name: "treatment_doctor")]
    private Collection $treatments;

    #[ORM\OneToMany(mappedBy: "doctor", targetEntity: Appointment::class)]
    private Collection $appointments;

    #[ORM\OneToMany(mappedBy: "doctor", targetEntity: Availability::class)]
    private Collection $availabilities;

    #[ORM\OneToMany(mappedBy: "doctor", targetEntity: AppointmentSlot::class)]
    private Collection $appointmentSlots;

    #[ORM\OneToMany(mappedBy: "doctor", targetEntity: AvailabilityOverride::class)]
    private Collection $availabilitiesOverride;

    public function __construct()
    {
        $this->patients = new ArrayCollection();
        $this->treatments = new ArrayCollection();
        $this->appointments = new ArrayCollection();
        $this->availabilities = new ArrayCollection();
        $this->appointmentSlots = new ArrayCollection();
        $this->availabilitiesOverride = new ArrayCollection();
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

    public function addTreatment(Treatment $treatment): self
    {
        if (!$this->treatments->contains($treatment)) {
            $this->treatments[] = $treatment;
            $treatment->addDoctor($this);
        }
        return $this;
    }

    public function removeTreatment(Treatment $treatment): self
    {
        if ($this->treatments->removeElement($treatment)) {
            $treatment->removeDoctor($this);
        }
        return $this;
    }

    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function getAvailabilities(): Collection
    {
        return $this->availabilities;
    }

    public function getCenter(): ?Center
    {
        return $this->center;
    }

    public function setCenter(?Center $center): static
    {
        $this->center = $center;
        return $this;
    }

    public function getAppointmentSlots(): Collection
    {
        return $this->appointmentSlots;
    }

    public function getAvailabilitiesOverride(): Collection
    {
        return $this->availabilitiesOverride;
    }
}