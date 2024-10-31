<?php

// src/Controller/ForgotPasswordController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForgotPasswordController extends AbstractController
{
    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function index(Request $request): Response
    {
        // Logique pour traiter la demande de réinitialisation du mot de passe
        return $this->render('security/forgot_password.html.twig');
    }
}
