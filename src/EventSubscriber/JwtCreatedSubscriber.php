<?php

namespace App\EventSubscriber;

use App\Entity\Patient;
use App\Entity\Doctor;
use App\Entity\AdminStaff;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;

class JwtCreatedSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            JWTCreatedEvent::class => 'onJWTCreated',
        ];
    }

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $user = $event->getUser();
        $payload = $event->getData();

        error_log('JWT User class: ' . get_class($user));
        error_log('JWT before modification: ' . json_encode($payload));

        if ($user instanceof Patient || $user instanceof Doctor || $user instanceof AdminStaff) {
            $payload['id'] = $user->getId();
            $payload['roles'] = $user->getRoles();
            $payload['email'] = $user->getUserIdentifier();
        } else {
            throw new \LogicException('User object is not an entity with getId');
        }

        $event->setData($payload);
        error_log('JWT after modification: ' . json_encode($payload));
    }
}