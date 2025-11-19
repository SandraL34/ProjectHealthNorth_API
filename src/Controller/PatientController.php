<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\Doctor;
use App\Entity\EmergencyContact;
use App\Entity\Option;
use App\Entity\Payment;
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

        $doctor = $patient->getAttendingPhysician();
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
            'attendingPhysician' => $doctor ? [
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

    #[Route('/api/patient/me', name: 'api_patient_update', methods: ['PUT'])]
    public function update(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof Patient) {
            throw $this->createAccessDeniedException('Seul un patient peut modifier son dossier.');
        }

        $data = json_decode($request->getContent(), true);

        $user->setFirstname($data['firstname'] ?? $user->getFirstname());
        $user->setLastname($data['lastname'] ?? $user->getLastname());
        $user->setEmail($data['email'] ?? $user->getEmail());
        $user->setPhoneNumber($data['phoneNumber'] ?? $user->getPhoneNumber());
        $user->setPostalAddress($data['postalAddress'] ?? $user->getPostalAddress());
        $user->setAllergy($data['allergy'] ?? $user->getAllergy());
        $user->setMedicalTraitmentDisease($data['medicalTraitmentDisease'] ?? $user->getMedicalTraitmentDisease());
        $user->setMedicalHistory($data['medicalHistory'] ?? $user->getMedicalHistory());
        $user->setPicture($data['picture'] ?? $user->getPicture());
        $user->setSocialsecurityNumber($data['socialsecurityNumber'] ?? $user->getSocialsecurityNumber());
        $user->setSocialsecurityRegime($data['socialsecurityRegime'] ?? $user->getSocialsecurityRegime());
        $user->setHealthcareInsurance($data['healthcareInsurance'] ?? $user->getHealthcareInsurance());
        $user->setEmergencyContact($data['emergencyContact'] ?? $user->getEmergencyContact());
        $user->setOption($data['option'] ?? $user->getOption());

        if (isset($data['attendingPhysicianId'])) {
            $doctor = $doctrine->getRepository(Doctor::class)->find($data['attendingPhysicianId']);
            $user->setAttendingPhysician($doctor);
        }

        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json([
            'message' => 'Dossier médical mis à jour avec succès',
            'patient' => [
                'id' => $user->getId(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'email' => $user->getEmail(),
                'phoneNumber' => $user->getPhoneNumber(),
                'postalAddress' => $user->getPostalAddress(),
                'allergy' => $user->getAllergy(),
                'medicalTraitmentDisease' => $user->getMedicalTraitmentDisease(),
                'medicalHistory' => $user->getMedicalHistory(),
                'picture' => $user->getPicture(),
                'socialsecurityNumber' => $user->getSocialsecurityNumber(),
                'socialsecurityRegime' => $user->getSocialsecurityRegime(),
                'healthcareInsurance' => $user->getHealthcareInsurance(),
                'emergencyContact' => $user->getEmergencyContact(),
                'attendingPhysicianId' => $user->getAttendingPhysician(),
                'option' => $user->getOption(),
            ]
        ]);
    }
}