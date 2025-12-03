<?php

namespace App\Controller;

use App\Entity\Patient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PatientRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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

    #[Route('/api/registration', name: 'api_registration', methods:['POST'])]
    function registration(Request $request, EntityManagerInterface $em, PatientRepository $patientRepo,
    UserPasswordHasherInterface $passwordHasher): JsonResponse 
    {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return $this->json(['error' => 'Invalid JSON'], 400);
        }

        if (empty($data['email']) || empty($data['password']) || empty($data['phoneNumber'])) {
            return $this->json(['error' => 'Missing fields'], 400);
        }

        if ($patientRepo->findOneBy(['email' => $data['email']])) {
            return $this->json(['error' => 'Email already used'], 409);
        }

        $patient = new Patient();
        $hashedPassword = $passwordHasher->hashPassword($patient, $data['password']);
        $patient->setemail($data['email'])
                ->setPassword($hashedPassword)
                ->setPhoneNumber($data['phoneNumber']);

        $em->persist($patient);
        $em->flush();

        return $this->json(['success' => true, 'patientId' => $patient->getId()], 201);
    }
}