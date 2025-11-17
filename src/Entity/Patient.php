<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

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

    #[ORM\ManyToOne(targetEntity: EmergencyContact::class, inversedBy: "patients")]
    #[ORM\JoinColumn(name: "emergency_contact_id", referencedColumnName: "id", nullable: true)]
    private ?EmergencyContact $emergencyContact = null;

    #[ORM\ManyToOne(targetEntity: Doctor::class, inversedBy: "patients")]
    #[ORM\JoinColumn(name: "attending_physician_id", referencedColumnName: "id", nullable: true)]
    private ?Doctor $attendingPhysician = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $appointmentId = null;

    #[ORM\ManyToOne(targetEntity: Option::class, inversedBy: "patients")]
    #[ORM\JoinColumn(name: "option_id", referencedColumnName: "id", nullable: true)]
    private ?Option $option = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $alarmId = null;

    #[ORM\OneToOne(targetEntity: Payment::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: "payment_id", referencedColumnName: "id", nullable: true)]
    private ?Payment $payment = null;

    public function getId(): ?int { return $this->id; }

    public function getFirstname(): ?string { return $this->firstname; }
    public function setFirstname(?string $firstname): static { $this->firstname = $firstname; return $this; }

    public function getLastname(): ?string { return $this->lastname; }
    public function setLastname(?string $lastname): static { $this->lastname = $lastname; return $this; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(?string $email): static { $this->email = $email; return $this; }

    public function getPassword(): ?string { return $this->password; }
    public function setPassword(?string $password): static { $this->password = $password; return $this; }

    public function getPostalAddress(): ?string { return $this->postalAddress; }
    public function setPostalAddress(?string $postalAddress): static { $this->postalAddress = $postalAddress; return $this; }

    public function getSocialsecurityNumber(): ?string { return $this->socialsecurityNumber; }
    public function setSocialsecurityNumber(?string $num): static { $this->socialsecurityNumber = $num; return $this; }

    public function getPhoneNumber(): ?string { return $this->phoneNumber; }
    public function setPhoneNumber(?string $num): static { $this->phoneNumber = $num; return $this; }

    public function getPicture(): ?string { return $this->picture; }
    public function setPicture(?string $pic): static { $this->picture = $pic; return $this; }

    public function getSocialsecurityRegime(): ?string { return $this->socialsecurityRegime; }
    public function setSocialsecurityRegime(?string $reg): static { $this->socialsecurityRegime = $reg; return $this; }

    public function getHealthcareInsurance(): ?string { return $this->healthcareInsurance; }
    public function setHealthcareInsurance(?string $ins): static { $this->healthcareInsurance = $ins; return $this; }

    public function getAllergy(): ?string { return $this->allergy; }
    public function setAllergy(?string $al): static { $this->allergy = $al; return $this; }

    public function getMedicalTraitmentDisease(): ?string { return $this->medicalTraitmentDisease; }
    public function setMedicalTraitmentDisease(?string $dis): static { $this->medicalTraitmentDisease = $dis; return $this; }

    public function getMedicalHistory(): ?string { return $this->medicalHistory; }
    public function setMedicalHistory(?string $his): static { $this->medicalHistory = $his; return $this; }

    public function getEmergencyContact(): ?EmergencyContact { return $this->emergencyContact; }
    public function setEmergencyContact(?EmergencyContact $emergencyContact): static { $this->emergencyContact = $emergencyContact; return $this; }

    public function getAttendingPhysician(): ?Doctor { return $this->attendingPhysician; }
    public function setAttendingPhysician(?Doctor $doctor): static { $this->attendingPhysician = $doctor; return $this; }

    public function getAppointmentId(): ?int { return $this->appointmentId; }
    public function setAppointmentId(?int $id): static { $this->appointmentId = $id; return $this; }

    public function getOption(): ?Option { return $this->option; }
    public function setOption(?Option $option): static { $this->option = $option; return $this; }

    public function getAlarmId(): ?int { return $this->alarmId; }
    public function setAlarmId(?int $id): static { $this->alarmId = $id; return $this; }

    public function getPayment(): ?Payment { return $this->payment; }
    public function setPayment(?Payment $payment): static { $this->payment = $payment; return $this; }

    public function getRoles(): array { return ['ROLE_PATIENT']; }

    public function getUserIdentifier(): string { return $this->email; }

    public function eraseCredentials(): void {}
}
