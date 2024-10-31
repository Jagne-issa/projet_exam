<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\UserCreatedSuccessEvent;

class UserSubscriber implements EventSubscriberInterface
{

    public function onUserCreated(UserCreatedEvent $event)
    {
        $user = $event->getUser();
    
        $email = (new Email())
            ->from('issa@gmail.com')
            ->to($user->getEmail())
            ->subject('Bienvenue sur notre plateforme !')
            ->html('<p>Merci de vous être inscrit !</p>');
    
        $this->mailer->send($email);
    }

    
    
    // Cette méthode sera appelée lors d'un succès de connexion
    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        // Logique à exécuter lors d'une connexion réussie
        // Par exemple : enregistrement d'un événement ou redirection
    }

    

    // Définit les événements auxquels cet abonné s'inscrit
    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess', // Mappez l'événement au gestionnaire de l'événement
        ];
    }
}
