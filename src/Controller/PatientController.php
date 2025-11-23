<?php

namespace App\Controller;

use App\Entity\Patient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class PatientController extends AbstractController
{
    #[Route('/api/patient/medicalRecord', name: 'api_patient_medicalRecord', methods: ['GET'])]
    public function me(ManagerRegistry $doctrine): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof Patient) {
            throw $this->createAccessDeniedException('Seul un patient peut accéder à ce endpoint.');
        }

        $patient = $doctrine->getRepository(Patient::class)->find($user->getId());

        $doctor = $patient->getDoctor();
        $emergencyContact = $patient->getEmergencyContact();
        $option = $patient->getOption();
        $payment = $patient->getPayments()->last();

        return $this->json([
            'id' => $patient->getId(),
            'firstname' => $patient->getFirstname(),
            'lastname' => $patient->getLastname(),
            'email' => $patient->getEmail(),
            'password' => $patient->getPassword(),
            'phoneNumber' => $patient->getPhoneNumber(),
            'postalAddress' => $patient->getPostalAddress(),
            'allergy' => $patient->getAllergy(),
            'medicalTraitmentDisease' => $patient->getMedicalTraitmentDisease(),
            'medicalHistory' => $patient->getMedicalHistory(),
            'picture' => $patient->getPicture(),
            'socialsecurityNumber' => $patient->getSocialsecurityNumber(),
            'socialsecurityRegime' => $patient->getSocialsecurityRegime(),
            'healthcareInsurance' => $patient->getHealthcareInsurance(),
            'emergencyContact' => $emergencyContact ? [
            'firstname' => $emergencyContact->getFirstname(),
            'lastname' => $emergencyContact->getLastname(),
            'phoneNumber' => $emergencyContact->getPhoneNumber()
            ] : null,
            'doctor' => $doctor ? [
            'firstname' => $doctor->getFirstname(),
            'lastname' => $doctor->getLastname()
            ] : null,
            'option' => $option ? [
            'communicationForm' => $option->getCommunicationForm(),
            'privateRoom' => $option->isPrivateRoom(),
            'television' => $option->isTelevision(),
            'wifi' => $option->isWifi(),
            'diet' => $option->getDiet()
            ] : null,
            'payment' => $payment ? [
            'cardNumber' => $payment->getCardNumber(),
            'expirationDateMonth' => $payment->getExpirationDateMonth(),
            'secretCode' => $payment->getSecretCode(),
            'ownerName' => $payment->getOwnerName(),
            'expirationDateYear' => $payment->getExpirationDateYear()
            ] : null,
        ]);
    }
}