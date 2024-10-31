<?php

// src/Controller/LoginController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, redirigez-le ailleurs (par exemple, vers la page d'accueil)
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

            // Récupération d'erreur de connexion s'il y en a
            $error = $authenticationUtils->getLastAuthenticationError();
            $lastUsername = $authenticationUtils->getLastUsername();
    
            return $this->render('login/index.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error,
            ]);
        

        // // obtenir l'erreur de connexion si elle existe
        // $error = $authenticationUtils->getLastAuthenticationError();
        // // dernier nom d'utilisateur entré par l'utilisateur
        // $lastUsername = $authenticationUtils->getLastUsername();

        // return $this->render('security/login.html.twig', [
        //     'last_username' => $lastUsername,
        //     'error' => $error,
        // ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
