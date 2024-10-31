<?php 

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
// use Symfony\Component\Security\Http\Authenticator\Passport\Badge\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;


class AppCustomAuthenticator extends AbstractAuthenticator
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'app_login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $login = $request->request->get('login');
        $password = $request->request->get('password');

        // Déboguer pour voir les valeurs récupérées
        dump($login, $password);

        // Vérifiez si les valeurs sont nulles
        if (empty($login)) {
                // Ajoutez ici un message d'erreur pour le login
                throw new \InvalidArgumentException('Le nom d\'utilisateur ou l\'email ne doit pas être vide.');
        }

        if (empty($password)) {
                // Ajoutez ici un message d'erreur pour le mot de passe
                throw new \InvalidArgumentException('Le mot de passe ne doit pas être vide.');
        }

        return new Passport(
                new UserBadge($login),
                new PasswordCredentials($password)
        );
    }




    // public function authenticate(Request $request): Passport
    // {
    //     // Récupération des données de la requête
    //     $email = $request->request->get('email');
    //     $password = $request->request->get('password');

    //     // Vérification si l'email est bien récupéré
    //     // Vérification si l'email est bien récupéré
    //     if (null === $email || null === $password) {
    //        throw new \InvalidArgumentException('Email and password must not be null');
    //     }
    //     if (!$request->isMethod('POST')) {
    //         throw new \UnexpectedValueException('Request method must be POST.');
    //     }
        

    //     // Création du Passport avec UserBadge
    //     return new Passport(
    //             new UserBadge($email),
    //             new PasswordCredentials($password)
    //     );
    // }

    


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->router->generate('app_home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new RedirectResponse($this->router->generate('app_login'));
    }
}
