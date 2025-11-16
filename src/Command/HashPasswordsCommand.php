<?php

namespace App\Command;

use App\Entity\Patient;
use App\Entity\Doctor;
use App\Entity\AdminStaff;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HashPasswordsCommand extends Command
{
    protected static $defaultName = 'app:hash-passwords';

    private $em;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure()
    {
        $this->setDescription('Hash les mots de passe existants des utilisateurs.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entities = [
            Patient::class => 'Patient',
            Doctor::class => 'Doctor',
            AdminStaff::class => 'AdminStaff'
        ];

        foreach ($entities as $class => $name) {
            $users = $this->em->getRepository($class)->findAll();

            foreach ($users as $user) {
                $plainPassword = $user->getPassword();

                // Vérifie si le mot de passe n’est pas déjà hashé (optionnel)
                if (strlen($plainPassword) < 60) { // bcrypt hash ~ 60 caractères
                    $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
                    $user->setPassword($hashedPassword);
                    $output->writeln("Mot de passe hashé pour {$name} : {$user->getEmail()}");
                }
            }
        }

        $this->em->flush();
        $output->writeln("Tous les mots de passe ont été hashés !");
        return Command::SUCCESS;
    }
}