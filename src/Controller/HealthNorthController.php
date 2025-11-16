<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Repository\PatientRepository;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class HealthNorthController extends AbstractController
{
    #[Route('/healthnorth/api/patients', name: 'patients', methods: ['GET'])]
    public function getPatientList(PatientRepository $patientRepository, SerializerInterface $serializer): JsonResponse
    {
        $patientList = $patientRepository->findAll();
        $jsonPatientList = $serializer->serialize($patientList, 'json');

        return new JsonResponse([
            $jsonPatientList, Response::HTTP_OK, [], true
        ]);
    }

    #[Route('/healthnorth/api/patients/{id}', name: 'patient', methods: ['GET'])]
    public function getPatient(int $id, PatientRepository $patientRepository, SerializerInterface $serializer): JsonResponse
    {
        $patient = $patientRepository->find($id);
        if ($patient) {
            $jsonPatient = $serializer->serialize($patient, 'json');

            return new JsonResponse([
                $jsonPatient, Response::HTTP_OK, [], true
            ]);
        }
        return new JsonResponse([
            null, Response::HTTP_NOT_FOUND
        ]);
    }

    #[Route('/healthnorth/api/patient/{id}', name: 'patientName', methods: ['GET'])]
    public function getPatientName(int $id, PatientRepository $patientRepository, SerializerInterface $serializer): JsonResponse
    {
        $patient = $patientRepository->find($id);
        if ($patient) {
            $jsonPatient = $serializer->serialize($patient, 'json');

            return new JsonResponse([
				'Nom' => $patient->getLastname(),
				'Prenom' => $patient->getFirstname(),
				'Email' => $patient->getEmail(), 
                'Response' => Response::HTTP_OK, 
                'Header' => [], 
                'Found' => true
            ]);
        }
        return new JsonResponse([
            null, Response::HTTP_NOT_FOUND
        ]);
    }

    #[Route('/healthnorth/api/payments/{id}', name: 'payment', methods: ['GET'])]
    public function getPayment(int $id, PaymentRepository $paymentRepository, SerializerInterface $serializer): JsonResponse
    {
        $payment = $paymentRepository->find($id);
        if ($payment) {
            $jsonPayment = $serializer->serialize($payment, 'json');

            return new JsonResponse([
                $jsonPayment, Response::HTTP_OK, [], true
            ]);
        }
        return new JsonResponse([
            null, Response::HTTP_NOT_FOUND
        ]);
    }

    #[Route('/healthnorth/api/payments/{id}', name: 'deletePayment', methods: ['DELETE'])]
    public function deletePayment(Payment $payment, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($payment);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/healthnorth/api/payments', name: 'createPayment', methods: ['POST'])]
    public function createPayment(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, 
    UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $payment = $serializer->deserialize($request->getContent(), Payment::class, 'json');
        $em->persist($payment);
        $em->flush();

        $jsonPayment = $serializer->serialize($payment, 'json', ['groups' => 'getPayments']);

        $location = $urlGenerator->generate('payment', ['id'=> $payment->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonPayment, Response::HTTP_CREATED, ['Location' => $location], true);
    }

    #[Route('/healthnorth/api/payments/{id}', name: 'updatePayment', methods: ['PUT'])]
    public function updatePayment(int $id, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, 
    PaymentRepository $paymentRepository): JsonResponse
    {
        $currentPayment = $paymentRepository->find($id);
        if (!$currentPayment) {
            return new JsonResponse(['error' => 'Payment not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $serializer->deserialize($request->getContent(), 
        Payment::class, 
        'json',
        [AbstractNormalizer::OBJECT_TO_POPULATE => $currentPayment]);

        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}