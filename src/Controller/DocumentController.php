<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Document;
use App\Entity\Patient;
use App\Entity\Doctor;
use App\Entity\AdminStaff;
use App\Entity\Appointment;

class DocumentController extends AbstractController
{
    private string $uploadDir;

    public function __construct(string $projectDir)
    {
        $this->uploadDir = $projectDir . '/var/uploads/documents';
    }


    private function isAdmin(): bool
    {
        return $this->getUser() instanceof AdminStaff;
    }

    private function isDoctor(): bool
    {
        return $this->getUser() instanceof Doctor;
    }

    private function isPatient(): bool
    {
        return $this->getUser() instanceof Patient;
    }

    private function getUploaderIdentifier(): string
    {
        $user = $this->getUser();
        if ($user instanceof AdminStaff) {
            return 'Admin · ' . $user->getFirstname() . ' ' . $user->getLastname();
        }
        if ($user instanceof Doctor) {
            return 'Dr · ' . $user->getFirstname() . ' ' . $user->getLastname();
        }
        if ($user instanceof Patient) {
            return 'Patient · ' . $user->getFirstname() . ' ' . $user->getLastname();
        }
        return $user->getUserIdentifier();
    }


    #[Route('/api/documents/upload', name: 'api_document_upload', methods: ['POST'])]
    public function upload(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        if (!$this->isAdmin() && !$this->isPatient()) {
            return $this->json(['error' => 'Forbidden'], 403);
        }

        $file = $request->files->get('file');
        if (!$file) {
            return $this->json(['error' => 'No file provided'], 400);
        }

        $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return $this->json(['error' => 'Type de fichier non autorisé (PDF, JPG, PNG uniquement)'], 400);
        }

        if ($file->getSize() > 10 * 1024 * 1024) {
            return $this->json(['error' => 'Fichier trop volumineux (10 Mo max)'], 400);
        }

        $patientId = $request->request->get('patientId');
        if (!$patientId) {
            return $this->json(['error' => 'patientId requis'], 400);
        }

        $patient = $em->getRepository(Patient::class)->find($patientId);
        if (!$patient) {
            return $this->json(['error' => 'Patient introuvable'], 404);
        }

        if ($this->isPatient()) {
            /** @var Patient $user */
            if ($user->getId() !== $patient->getId()) {
                return $this->json(['error' => 'Forbidden'], 403);
            }
        }

        $appointment = null;
        $appointmentId = $request->request->get('appointmentId');
        if ($appointmentId) {
            $appointment = $em->getRepository(Appointment::class)->find($appointmentId);
            if (!$appointment) {
                return $this->json(['error' => 'Rendez-vous introuvable'], 404);
            }
        }

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0775, true);
        }

        $displayName = $request->request->get('displayName', $file->getClientOriginalName());
        $extension    = $file->getClientOriginalExtension();
        $filename     = uniqid('doc_') . '_' . time() . '.' . $extension;

        $file->move($this->uploadDir, $filename);

        $document = new Document();
        $document->setFilename($filename)
                ->setDisplayName($displayName)
                ->setType($request->request->get('type', 'autre'))
                ->setDateUpload(new \DateTimeImmutable())
                ->setPatient($patient)
                ->setAppointment($appointment);

        $em->persist($document);
        $em->flush();

        return $this->json([
            'success'      => true,
            'id'           => $document->getId(),
            'displayName' => $document->getDisplayName(),
            'type'         => $document->getType(),
            'dateUpload'   => $document->getDateUpload()->format('Y-m-d H:i'),
        ], 201);
    }


    #[Route('/api/documents/patient/{id}', name: 'api_document_list', methods: ['GET'])]
    public function listForPatient(int $id, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $patient = $em->getRepository(Patient::class)->find($id);
        if (!$patient) {
            return $this->json(['error' => 'Patient introuvable'], 404);
        }

        if ($this->isPatient()) {
            /** @var Patient $user */
            if ($user->getId() !== $patient->getId()) {
                return $this->json(['error' => 'Forbidden'], 403);
            }
        }

        $documents = $em->getRepository(Document::class)->findBy(
            ['patient' => $patient],
            ['dateUpload' => 'DESC']
        );

        $result = array_map(function (Document $doc) {
            return [
                'id'              => $doc->getId(),
                'displayName'    => $doc->getDisplayName(),
                'type'            => $doc->getType(),
                'dateUpload'      => $doc->getDateUpload()?->format('Y-m-d H:i'),
                'appointmentId'   => $doc->getAppointment()?->getId(),
                'appointmentDate' => $doc->getAppointment()?->getDate()?->format('Y-m-d'),
            ];
        }, $documents);

        return $this->json($result);
    }


    #[Route('/api/documents/{id}/download', name: 'api_document_download', methods: ['GET'])]
    public function download(int $id, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $document = $em->getRepository(Document::class)->find($id);
        if (!$document) {
            return $this->json(['error' => 'Document introuvable'], 404);
        }

        if ($this->isPatient()) {
            /** @var Patient $user */
            if ($user->getId() !== $document->getPatient()->getId()) {
                return $this->json(['error' => 'Forbidden'], 403);
            }
        }

        $filepath = $this->uploadDir . '/' . $document->getFilename();
        if (!file_exists($filepath)) {
            return $this->json(['error' => 'Fichier introuvable sur le serveur'], 404);
        }

        return $this->file($filepath, $document->getDisplayName());
    }


    #[Route('/api/documents/{id}', name: 'api_document_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $document = $em->getRepository(Document::class)->find($id);
        if (!$document) {
            return $this->json(['error' => 'Document introuvable'], 404);
        }

        if ($this->isDoctor()) {
            return $this->json(['error' => 'Forbidden'], 403);
        }

        if ($this->isPatient()) {
            /** @var Patient $user */
            if ($user->getId() !== $document->getPatient()->getId()) {
                return $this->json(['error' => 'Forbidden'], 403);
            }
        }


        $filepath = $this->uploadDir . '/' . $document->getFilename();
        if (file_exists($filepath)) {
            unlink($filepath);
        }

        $em->remove($document);
        $em->flush();

        return $this->json(null, 204);
    }
}