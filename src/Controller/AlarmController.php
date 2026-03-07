<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Alarm;
use App\Entity\Appointment;
use App\Entity\Medicine;
use App\Repository\AlarmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class AlarmController extends AbstractController
{
    #[Route('/api/alarms', name: 'api_alarms', methods: ['GET'])]
    public function getAlarms(AlarmRepository $alarmRepository): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $alarms = $alarmRepository->findByPatient($user);

        $alarmList = [];

        foreach ($alarms as $alarm) {
            $alarmList[] = [
                'id' => $alarm->getId(),
                'dateTime' => $alarm->getDateTime()?->format('Y-m-d H:i:s'),
                'frequency' => $alarm->getFrequency(),
                'type' => $alarm->getType(),
                'title' => $alarm->getTitle(),
                'notification' => $alarm->getNotification(),
                'appointment' => $alarm->getAppointment()
                    ? [
                        'id' => $alarm->getAppointment()->getId(),
                        'title' => $alarm->getAppointment()->getTitle(),
                        'date' => $alarm->getAppointment()->getDate()?->format('Y-m-d'),
                        'time' => $alarm->getAppointment()->getTime()?->format('H:i:s'),
                    ]
                    : null,
                'medicine' => $alarm->getMedicine()
                    ? [
                        'id' => $alarm->getMedicine()->getId(),
                        'name' => $alarm->getMedicine()->getName(),   
                        'frequency' => $alarm->getMedicine()->getFrequency(),
                        'duration' => $alarm->getMedicine()->getDuration(),
                    ]
                    : null,
            ];
        }

        return new JsonResponse($alarmList);
    }

    #[Route('/api/alarms/{id}', name: 'api_alarms_change', methods:['PUT'])]
    public function changeAlarm(int $id, Request $request, AlarmRepository $alarmRepository, EntityManagerInterface $em): JsonResponse 
    {
        $data = json_decode($request->getContent(), true);

        $alarm = $alarmRepository->find($id);

        if (!$alarm) {
            return $this->json(['error' => 'Alarm not found'], 404);
        }

        if (isset($data['dateTime'])) {
            $alarm->setDateTime(new \DateTime($data['dateTime']));
        }

        if (isset($data['frequency'])) {
            $alarm->setFrequency($data['frequency']);
        }

        if (isset($data['type'])) {
            $alarm->setType($data['type']);
        }

        if (isset($data['title'])) {
            $alarm->setTitle($data['title']);
        }

        if (isset($data['notification'])) {
            $alarm->setNotification($data['notification']);
        }

        $em->flush();

        return new JsonResponse([
            'id' => $alarm->getId(),
            'dateTime' => $alarm->getDateTime()?->format('Y-m-d H:i:s'),
            'frequency' => $alarm->getFrequency(),
            'type' => $alarm->getType(),
            'title' => $alarm->getTitle(),
            'notification' => $alarm->getNotification(),
            'appointment' => $alarm->getAppointment()
                ? [
                    'id' => $alarm->getAppointment()->getId(),
                    'title' => $alarm->getAppointment()->getTitle(),
                    'date' => $alarm->getAppointment()->getDate()?->format('Y-m-d'),
                    'time' => $alarm->getAppointment()->getTime()?->format('H:i:s'),
                ]
                : null,
            'medicine' => $alarm->getMedicine()
                ? [
                    'id' => $alarm->getMedicine()->getId(),
                    'name' => $alarm->getMedicine()->getName(),   
                    'frequency' => $alarm->getMedicine()->getFrequency(),
                    'duration' => $alarm->getMedicine()->getDuration(),
                ]
                : null, 
        ]);
    }

    #[Route ('/api/alarms/add', name: 'api_alarms_add', methods:['POST'])]
    public function addAlarm(Request $request, AlarmRepository $alarmRepo, EntityManagerInterface $em): JsonResponse {

        $data = json_decode($request->getContent(), true);

        if (empty($data['dateTime']) || empty($data['frequency']) || empty($data['type']) || empty($data['title']) || empty($data['notification'])) {
            return $this->json(['error' => 'Missing fields'], 400);
        }

        $alarm = new Alarm();

        $alarm->setDateTime(new \DateTime($data['dateTime']))
            ->setFrequency($data['frequency'])
            ->setType($data['type'])
            ->setTitle($data['title'])
            ->setNotification($data['notification']);

        $em->persist($alarm);
        $em->flush();

        return new JsonResponse(['success' => true, 'alarmId' => $alarm->getId()],Response::HTTP_CREATED);
    }

    #[Route('/api/alarms/{id}', name: 'api_alarms_delete', methods: ['DELETE'])] 
    public function deleteAlarm(int $id, AlarmRepository $alarmRepo, EntityManagerInterface $em): JsonResponse {
        $alarm = $alarmRepo->find($id);

        if (!$alarm) {
            return $this->json(['error' => 'Alarm not found'], 404);
        }

        $em->remove($alarm);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}