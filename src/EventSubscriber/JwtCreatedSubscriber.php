<?php

namespace App\EventSubscriber;

use App\Entity\Patient;
use App\Entity\Doctor;
use App\Entity\AdminStaff;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JwtCreatedSubscriber implements EventSubscriberInterface
{
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

        // Ajouter l'ID uniquement si c'est une de nos entités
        if ($user instanceof Patient || $user instanceof Doctor || $user instanceof AdminStaff) {
            $payload['id'] = $user->getId();
            $payload['roles'] = $user->getRoles();
            $payload['email'] = $user->getUserIdentifier(); 
        } else {
            throw new \LogicException('User object is not an entity with getId');
        }

        $event->setData($payload);
    }
}