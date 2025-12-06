<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\EmergencyContact;
use App\Entity\Doctor;
use App\Entity\Option;
use App\Entity\Payment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PatientRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Response;

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
            'id' => $doctor->getId(),
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

    #[Route('/registration', name: 'registration', methods:['POST'])]
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

    #[Route('/api/medicalrecord/change', name: 'api_medicalrecord_change', methods:['PUT'])]
    function changeMedicalRecord(Request $request, EntityManagerInterface $em, PatientRepository $patientRepo,
    UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine): JsonResponse
    {
        $patient = $this->getUser();

        if (!$patient instanceof Patient) {  
            return $this->json(['error' => 'Unauthorized'], 401);  
        }  

        $em = $doctrine->getManager();  
        $data = json_decode($request->getContent(), true);  

        if (!is_array($data)) {  
            return $this->json(['error' => 'Invalid JSON'], 400);  
        }  

        $fields = ['firstname', 'lastname', 'email', 'phoneNumber', 'postalAddress', 'allergy', 'medicalTraitmentDisease', 'medicalHistory', 'picture', 'socialsecurityNumber', 'socialsecurityRegime', 'healthcareInsurance'];  
        foreach ($fields as $field) {  
            if (isset($data[$field])) {  
                $setter = 'set' . ucfirst($field);  
                $patient->$setter($data[$field]);  
            }  
        }  
    
        if (!empty($data['password'])) {  
            $hashed = $passwordHasher->hashPassword($patient, $data['password']);  
            $patient->setPassword($hashed);  
        }  

        if (isset($data['emergencyContact'])) {  
            $contactData = $data['emergencyContact'];  
            $emergencyContact = $patient->getEmergencyContact() ?? new EmergencyContact();  

            if (!$patient->getEmergencyContact()) {  
                $patient->setEmergencyContact($emergencyContact);  
                $em->persist($emergencyContact);  
            }  

            foreach (['firstname', 'lastname', 'phoneNumber'] as $f) {  
                if (isset($contactData[$f])) {  
                    $setter = 'set' . ucfirst($f);  
                    $emergencyContact->$setter($contactData[$f]);  
                }  
            }  
        }  
    
        if (isset($data['doctor']['id'])) {
            $doctorId = $data['doctor']['id'];
            $doctorEntity = $doctrine->getRepository(Doctor::class)->find($doctorId);

            if ($doctorEntity) {
                $patient->setDoctor($doctorEntity);
            }
        }
    
        if (isset($data['option'])) {  
            $optionData = $data['option'];  
            $option = $patient->getOption() ?? new Option();  

            if (!$patient->getOption()) {  
                $patient->setOption($option);  
                $em->persist($option);  
            }  

            $optionFields = ['communicationForm', 'privateRoom', 'television', 'wifi', 'diet'];  
            foreach ($optionFields as $f) {  
                if (isset($optionData[$f])) {  
                    $setter = 'set' . ucfirst($f);  
                    $option->$setter($optionData[$f]);  
                }  
            }  
        }  

        if (isset($data['payment'])) {  
            $paymentData = $data['payment'];  
            $payment = $patient->getPayments()->last() ?: null;  

            if (!$payment) {  
                $payment = new Payment();  
                $patient->addPayment($payment);  
                $em->persist($payment);  
            }  

            $paymentFields = ['cardNumber', 'expirationDateMonth', 'expirationDateYear', 'ownerName', 'secretCode'];  
            foreach ($paymentFields as $f) {  
                if (isset($paymentData[$f])) {  
                    $setter = 'set' . ucfirst($f);  
                    $value = $paymentData[$f];  
                    if (in_array($f, ['expirationDateMonth', 'expirationDateYear'])) {  
                        $value = (int) $value;  
                    }  
                    $payment->$setter($value);  
                }  
            }  
        }  

        $em->flush();  

        $doctor = $patient->getDoctor();  
        $emergencyContact = $patient->getEmergencyContact();  
        $option = $patient->getOption();  
        $payment = $patient->getPayments()->last();  

        return $this->json([  
            'success' => true,  
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
                'id' => $doctor->getId(),
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

    #[Route('/api/medicalrecord/delete', name: 'api_medicalRecord_delete', methods: ['DELETE'])]
    public function deleteMedicalRecord(EntityManagerInterface $em) {
        $patient = $this->getUser();

        if (!$patient instanceof Patient) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $em->remove($patient);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}