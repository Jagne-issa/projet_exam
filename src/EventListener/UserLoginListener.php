<?php

namespace App\EventListener;

use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

class UserLoginListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        // Récupérer l'utilisateur
        $user = $event->getUser();

        // Logique supplémentaire à exécuter lors d'un succès de connexion
        $this->logger->info('User logged in: ' . $user->getEmail());

        // Vous pouvez ajouter d'autres actions ici, comme émettre des événements ou modifier des données
    }
}
