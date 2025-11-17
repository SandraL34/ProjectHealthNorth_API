<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\Doctor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class PatientController extends AbstractController
{
    /**
     * Récupère le dossier médical du patient connecté
     */
    #[Route('/api/patient/me', name: 'api_patient_me', methods: ['GET'])]
    public function me(ManagerRegistry $doctrine): JsonResponse
    {
        $user = $this->getUser();

        // Vérification : l'utilisateur est bien un Patient
        if (!$user instanceof Patient) {
            throw $this->createAccessDeniedException('Seul un patient peut accéder à ce endpoint.');
        }

        $patient = $doctrine->getRepository(Patient::class)->find($user->getId());

        $doctor = $patient->getAttendingPhysician();

        return $this->json([
            'id' => $patient->getId(),
            'firstname' => $patient->getFirstname(),
            'lastname' => $patient->getLastname(),
            'email' => $patient->getEmail(),
            'phoneNumber' => $patient->getPhoneNumber(),
            'postalAddress' => $patient->getPostalAddress(),
            'allergy' => $patient->getAllergy(),
            'medicalTraitmentDisease' => $patient->getMedicalTraitmentDisease(),
            'medicalHistory' => $patient->getMedicalHistory(),
            'picture' => $patient->getPicture(),
            'socialsecurityNumber' => $patient->getSocialsecurityNumber(),
            'socialsecurityRegime' => $patient->getSocialsecurityRegime(),
            'healthcareInsurance' => $patient->getHealthcareInsurance(),
            'emergencyContactId' => $patient->getEmergencyContactId(),
            'attendingPhysician' => $doctor ? [
            'firstname' => $doctor->getFirstname(),
            'lastname' => $doctor->getLastname()
            ] : null,
            'appointmentId' => $patient->getAppointmentId(),
            'optionId' => $patient->getOptionId(),
            'alarmId' => $patient->getAlarmId(),
            'paymentId' => $patient->getPaymentId(),
        ]);
    }

    /**
     * Met à jour le dossier médical du patient connecté
     */
    #[Route('/api/patient/me', name: 'api_patient_update', methods: ['PUT'])]
    public function update(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $user = $this->getUser();

        // Vérification : l'utilisateur est bien un Patient
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
        $user->setEmergencyContactId($data['emergencyContactId'] ?? $user->getEmergencyContactId());
        $user->setAppointmentId($data['appointmentId'] ?? $user->getAppointmentId());
        $user->setOptionId($data['optionId'] ?? $user->getOptionId());
        $user->setAlarmId($data['alarmId'] ?? $user->getAlarmId());
        $user->setPaymentId($data['paymentId'] ?? $user->getPaymentId());

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
                'emergencyContactId' => $user->getEmergencyContactId(),
                'attendingPhysicianId' => $user->getAttendingPhysician(),
                'appointmentId' => $user->getAppointmentId(),
                'optionId' => $user->getOptionId(),
                'alarmId' => $user->getAlarmId(),
                'paymentId' => $user->getPaymentId(),
            ]
        ]);
    }
}