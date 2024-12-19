<?php

namespace App\Controller;

use App\Form\LoginFormType;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;

class SecurityController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $form = $this->createForm(LoginFormType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Authentification réussie
            return $this->redirectToRoute('app_home'); // Redirigez l'utilisateur
        }

        if ($request->isMethod('POST')) {
            dump($request->request->all()); // Cela affichera les données de la requête
            exit;
        }        
    
        // Récupérez les informations pour le dernier utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();
    
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'form' => $form->createView(),
            'error' => $error,
        ]);


    }
    

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Ce message ne sera jamais affiché car Symfony gère la déconnexion.');
    }

    #[Route(path: '/login/google', name: 'google_login')]
    public function googleLogin(OAuth2ClientInterface $googleClient): Response
    {
        return $googleClient->redirect(['email', 'profile']);
    }
    
    #[Route(path: '/auth/google/callback', name: 'google_auth_callback')]
    public function googleAuthCallback(Request $request, OAuth2ClientInterface $googleClient): Response
    {
        // Traitement de l'utilisateur Google ici
        // Puis redirection
        return $this->redirectToRoute('app_home');
    }

    public function getCredentials(Request $request)
    {
        $email = $request->request->get('email');
        
        if ($email === null) {
            throw new BadRequestHttpException('Le champ email ne peut pas être null.');
        }
        
        return [
            'email' => $email,
            'password' => $request->request->get('password'),
        ];
    }
    
}
