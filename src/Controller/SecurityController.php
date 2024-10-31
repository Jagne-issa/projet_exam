<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Security; // Assurez-vous d'importer cette classe

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
    // Vérifiez si l'utilisateur est déjà connecté
    if ($this->getUser()) {
        return $this->redirectToRoute('app_home');
    }

    // Obtenez l'erreur de connexion s'il y en a une
    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();

    // Validation du champ email
    $email = $request->request->get('email');
    if ($email === null) {
        throw new BadRequestHttpException('Le champ email est requis.');
    }

    return $this->render('security/login.html.twig', [
        'last_username' => $lastUsername,
        'error' => $error,
    ]);
}


    // #[Route(path: '/login', name: 'app_login')]
    // public function login(AuthenticationUtils $authenticationUtils): Response
    // {
    //     // Vérifiez si l'utilisateur est déjà connecté
    //     if ($this->getUser()) {
    //         // Débogage
    //         dump('User is logged in, redirecting to home');
    //         return $this->redirectToRoute('app_home'); // Assurez-vous que 'app_home' est bien défini
    //     }

    //     // Obtenez l'erreur de connexion s'il y en a une
    //     $error = $authenticationUtils->getLastAuthenticationError();
    //     $lastUsername = $authenticationUtils->getLastUsername();

    //     // Débogage
    //     dump('Error: ', $error);
    //     dump('Last Username: ', $lastUsername); // Affiche le dernier nom d'utilisateur saisi

    //     return $this->render('security/login.html.twig', [
    //         'last_username' => $lastUsername,
    //         'error' => $error,
    //     ]);
    // }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/login/google', name: 'google_login')]
    public function googleLogin(OAuth2ClientInterface $googleClient): Response
    {
        // Redirigez vers le fournisseur d'authentification Google
        return $googleClient->redirect(['email', 'profile']); // Demande des scopes pour l'email et le profil
    }

    #[Route(path: '/auth/google/callback', name: 'google_auth_callback')]
    public function googleAuthCallback(Request $request, OAuth2ClientInterface $googleClient): Response
    {
        // Récupération de l'utilisateur
        $user = $googleClient->fetchUser();

        // Authentification de l'utilisateur dans votre application
        // Vous pouvez enregistrer ou mettre à jour l'utilisateur dans votre base de données ici
        // Ex: $this->saveUser($user);

        // Authentifiez l'utilisateur
        $this->loginUser($user);

        // Redirection après la connexion réussie
        return $this->redirectToRoute('app_home'); // Remplacez par votre route de redirection
    }

    private function loginUser($user): void
    {
        // Implémentez votre logique d'authentification ici
        // Par exemple, utilisez le service de sécurité pour authentifier l'utilisateur
        // Vous pouvez également créer ou mettre à jour l'utilisateur dans la base de données

        // Exemple de logique d'authentification
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->security->getTokenStorage()->setToken($token);
        $this->security->getSession()->set('_security_main', serialize($token));
    }
}
