<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login()
    {
        // Le JSON login est géré automatiquement par le firewall
        throw new \Exception('Cette méthode ne doit jamais être exécutée.');
    }
}