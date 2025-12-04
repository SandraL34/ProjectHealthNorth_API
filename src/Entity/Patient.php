<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "patient")]
class Patient implements UserInterface, PasswordAuthenticatedUserInterface
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
    private ?string $email = null;

    #[ORM\Column(type: "string", length: 256, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(type: "string", length: 512, nullable: true)]
    private ?string $postalAddress = null;

    #[ORM\Column(type: "string", length: 15, nullable: true)]
    private ?string $socialsecurityNumber = null;

    #[ORM\Column(type: "string", length: 10, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(type: "string", length: 512, nullable: true)]
    private ?string $picture = null;

    #[ORM\Column(type: "string", length: 256, nullable: true)]
    private ?string $socialsecurityRegime = null;

    #[ORM\Column(type: "string", length: 256, nullable: true)]
    private ?string $healthcareInsurance = null;

    #[ORM\Column(type: "string", length: 256, nullable: true)]
    private ?string $allergy = null;

    #[ORM\Column(type: "string", length: 1024, nullable: true)]
    private ?string $medicalTraitmentDisease = null;

    #[ORM\Column(type: "string", length: 1024, nullable: true)]
    private ?string $medicalHistory = null;

    #[ORM\ManyToOne(targetEntity: EmergencyContact::class, inversedBy: "patients", cascade: ['persist'])]
    #[ORM\JoinColumn(name: "emergency_contact_id", referencedColumnName: "id", nullable: true)]
    private ?EmergencyContact $emergencyContact = null;

    #[ORM\ManyToOne(targetEntity: Doctor::class, inversedBy: "patients")]
    #[ORM\JoinColumn(name: "doctor_id", referencedColumnName: "id", nullable: true)]
    private ?Doctor $doctor = null;

    #[ORM\ManyToOne(targetEntity: Option::class, inversedBy: "patients")]
    #[ORM\JoinColumn(name: "option_id", referencedColumnName: "id", nullable: true)]
    private ?Option $option = null;

    #[ORM\OneToMany(mappedBy: "patient", targetEntity: Payment::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $payments;

    #[ORM\OneToMany(mappedBy: "patient", targetEntity: Appointment::class)]
    private Collection $appointments;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
        $this->appointments = new ArrayCollection();
    }

    public function getId(): ?int { 
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

    public function getEmail(): ?string 
    { 
        return $this->email; 
    }
    public function setEmail(?string $email): static 
    { 
        $this->email = $email; 
        return $this; 
    }

    public function getPassword(): ?string 
    { 
        return $this->password; 
    }

    public function setPassword(?string $password): static 
    { 
        $this->password = $password; 
        return $this; 
    }

    public function getPostalAddress(): ?string 
    { 
        return $this->postalAddress; 
    }

    public function setPostalAddress(?string $postalAddress): static 
    { 
        $this->postalAddress = $postalAddress; 
        return $this; 
    }

    public function getSocialsecurityNumber(): ?string 
    { 
        return $this->socialsecurityNumber; 
    }

    public function setSocialsecurityNumber(?string $socialSecurityNumber): static 
    { 
        $this->socialsecurityNumber = $socialSecurityNumber; 
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

    public function getPicture(): ?string 
    { 
        return $this->picture; 
    }

    public function setPicture(?string $picture): static 
    { 
        $this->picture = $picture; 
        return $this; 
    }

    public function getSocialsecurityRegime(): ?string 
    { 
        return $this->socialsecurityRegime; 
    }

    public function setSocialsecurityRegime(?string $socialsecurityRegime): static 
    { $this->socialsecurityRegime = $socialsecurityRegime; 
        return $this; 
    }

    public function getHealthcareInsurance(): ?string 
    { 
        return $this->healthcareInsurance; 
    }

    public function setHealthcareInsurance(?string $healthcareInsurance): static 
    { 
        $this->healthcareInsurance = $healthcareInsurance; 
        return $this; 
    }

    public function getAllergy(): ?string 
    { 
        return $this->allergy; 
    }

    public function setAllergy(?string $allergy): static 
    { 
        $this->allergy = $allergy; 
        return $this; 
    }

    public function getMedicalTraitmentDisease(): ?string 
    { 
        return $this->medicalTraitmentDisease; 
    }

    public function setMedicalTraitmentDisease(?string $medicalTraitmentDisease): static 
    { $this->medicalTraitmentDisease = $medicalTraitmentDisease; 
        return $this; 
    }

    public function getMedicalHistory(): ?string 
    { 
        return $this->medicalHistory; 
    }

    public function setMedicalHistory(?string $medicalHistory): static 
    { 
        $this->medicalHistory = $medicalHistory; 
        return $this; 
    }

    public function getEmergencyContact(): ?EmergencyContact 
    { 
        return $this->emergencyContact; 
    }

    public function setEmergencyContact(?EmergencyContact $emergencyContact): static 
    { 
        $this->emergencyContact = $emergencyContact; 
        return $this; 
    }

    public function getDoctor(): ?Doctor 
    { 
        return $this->doctor; 
    }

    public function setDoctor(?Doctor $doctor): static 
    { 
        $this->doctor = $doctor; 
        return $this; 
    }

    public function getOption(): ?Option 
    { 
        return $this->option; 
    }

    public function setOption(?Option $option): static 
    { 
        $this->option = $option; 
        return $this; 
    }

    public function getRoles(): array 
    { 
        return ['ROLE_PATIENT']; 
    }

    public function getUserIdentifier(): string 
    { 
        return $this->email; 
    }

    public function eraseCredentials(): void {}

    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setPatient($this);
        }
        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            if ($payment->getPatient() === $this) {
                $payment->setPatient(null);
            }
        }
        return $this;
    }

    public function getAppointments(): Collection
    {
        return $this->appointments;
    }
}