<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Invoice;
use Symfony\Component\HttpFoundation\Request;

class InvoiceController extends AbstractController
{
    #[Route('/api/invoices', name: 'api_invoices_list', methods: ['GET'])]
    public function list(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $paidParam = $request->query->get('paid');

        $criteria = [];
        if ($paidParam !== null) {
            $criteria['isPaid'] = filter_var($paidParam, FILTER_VALIDATE_BOOLEAN);
        }

        $invoices = $em->getRepository(Invoice::class)->findBy($criteria);

        $result = array_map(function (Invoice $invoice) {
        $appointment = $invoice->getAppointment();
        
        $treatments = $appointment?->getTreatments();
        $treatment = ($treatments && !$treatments->isEmpty()) ? $treatments->first() : null;
        
        $patient = $appointment?->getPatient();
        $doctor = $appointment?->getDoctor();

        return [
            'id'     => $invoice->getId(),
            'isPaid' => $invoice->getIsPaid(),
            'price'  => $treatment?->getPrice(),
            'appointment' => $appointment ? [
                'id'   => $appointment->getId(),
                'date' => $appointment->getDate()?->format('Y-m-d'),
                'time' => $appointment->getTime()?->format('H:i:s'),
            ] : null,
            'treatment' => $treatment ? [
                'name'  => $treatment->getName(),
                'price' => $treatment->getPrice(),
            ] : null,
            'patient' => $patient ? [
                'firstname' => $patient->getFirstname(),
                'lastname'  => $patient->getLastname(),
            ] : null,
            'doctor' => $doctor ? [
                'firstname' => $doctor->getFirstname(),
                'lastname'  => $doctor->getLastname(),
            ] : null,
        ];
    }, $invoices);

        return $this->json($result);
    }

    #[Route('/api/invoices/{id}/pay', name: 'api_invoice_pay', methods: ['PATCH'])]
    public function markAsPaid(int $id, EntityManagerInterface $em): JsonResponse
    {
        $invoice = $em->getRepository(Invoice::class)->find($id);
        if (!$invoice) {
            return $this->json(['error' => 'Invoice not found'], 404);
        }

        $invoice->setIsPaid(true);
        $em->flush();

        return $this->json(['success' => true, 'id' => $invoice->getId(), 'isPaid' => true]);
    }
}