<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * Patient
 *
 * @ORM\Table(name="patient")
 * @ORM\Entity
 */
class Patient implements UserInterface, PasswordAuthenticatedUserInterface
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
     * @ORM\Column(name="firstname", type="string", length=256, nullable=true)
     */
    private $firstname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lastname", type="string", length=256, nullable=true)
     */
    private $lastname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=256, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=256, nullable=true)
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="postal_address", type="string", length=512, nullable=true)
     */
    private $postalAddress;

    /**
     * @var string|null
     *
     * @ORM\Column(name="socialsecurity_number", type="string", length=15, nullable=true)
     */
    private $socialsecurityNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone_number", type="string", length=10, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="picture", type="string", length=512, nullable=true)
     */
    private $picture;

    /**
     * @var string|null
     *
     * @ORM\Column(name="socialsecurity_regime", type="string", length=256, nullable=true)
     */
    private $socialsecurityRegime;

    /**
     * @var string|null
     *
     * @ORM\Column(name="healthcare_insurance", type="string", length=256, nullable=true)
     */
    private $healthcareInsurance;

    /**
     * @var string|null
     *
     * @ORM\Column(name="allergy", type="string", length=256, nullable=true)
     */
    private $allergy;

    /**
     * @var string|null
     *
     * @ORM\Column(name="medical_traitment_disease", type="string", length=1024, nullable=true)
     */
    private $medicalTraitmentDisease;

    /**
     * @var string|null
     *
     * @ORM\Column(name="medical_history", type="string", length=1024, nullable=true)
     */
    private $medicalHistory;

    /**
     * @var int|null
     *
     * @ORM\Column(name="emergency_contact_id", type="integer", nullable=true)
     */
    private $emergencyContactId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="attending_physician_id", type="integer", nullable=true)
     */
    private $attendingPhysicianId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="appointment_id", type="integer", nullable=true)
     */
    private $appointmentId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="option_id", type="integer", nullable=true)
     */
    private $optionId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="alarm_id", type="integer", nullable=true)
     */
    private $alarmId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="payment_id", type="integer", nullable=true)
     */
    private $paymentId;

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

    public function setSocialsecurityNumber(?string $socialsecurityNumber): static
    {
        $this->socialsecurityNumber = $socialsecurityNumber;

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
    {
        $this->socialsecurityRegime = $socialsecurityRegime;

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
    {
        $this->medicalTraitmentDisease = $medicalTraitmentDisease;

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

    public function getEmergencyContactId(): ?int
    {
        return $this->emergencyContactId;
    }

    public function setEmergencyContactId(?int $emergencyContactId): static
    {
        $this->emergencyContactId = $emergencyContactId;

        return $this;
    }

    public function getAttendingPhysicianId(): ?int
    {
        return $this->attendingPhysicianId;
    }

    public function setAttendingPhysicianId(?int $attendingPhysicianId): static
    {
        $this->attendingPhysicianId = $attendingPhysicianId;

        return $this;
    }

    public function getAppointmentId(): ?int
    {
        return $this->appointmentId;
    }

    public function setAppointmentId(?int $appointmentId): static
    {
        $this->appointmentId = $appointmentId;

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

    public function getAlarmId(): ?int
    {
        return $this->alarmId;
    }

    public function setAlarmId(?int $alarmId): static
    {
        $this->alarmId = $alarmId;

        return $this;
    }

    public function getPaymentId(): ?int
    {
        return $this->paymentId;
    }

    public function setPaymentId(?int $paymentId): static
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    public function getRoles(): array
    {
        if ($this instanceof AdminStaff) return ['ROLE_ADMIN'];
        if ($this instanceof Doctor) return ['ROLE_DOCTOR'];
        return ['ROLE_PATIENT'];
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials() {}

}
