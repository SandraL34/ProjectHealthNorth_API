<?php

namespace App\Security;

use App\Entity\Patient;
use App\Entity\Doctor;
use App\Entity\AdminStaff;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->em->getRepository(AdminStaff::class)->findOneBy(['email' => $identifier]);
        if ($user) return $user;

        $user = $this->em->getRepository(Doctor::class)->findOneBy(['email' => $identifier]);
        if ($user) return $user;

        $user = $this->em->getRepository(Patient::class)->findOneBy(['email' => $identifier]);
        if ($user) return $user;

        throw new \Exception('Utilisateur introuvable.');
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return in_array($class, [Patient::class, Doctor::class, AdminStaff::class], true);
    }
}