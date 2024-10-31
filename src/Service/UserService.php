<?php

namespace App\Service;

use App\Entity\User;
use App\Event\UserCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserService
{
    private EntityManagerInterface $entityManager;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function createUser(string $email, string $password): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password); // Assurez-vous de hasher le mot de passe ici

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Émettre l'événement
        $event = new UserCreatedEvent($user);
        $this->eventDispatcher->dispatch($event, UserCreatedEvent::NAME);

        return $user;
    }
}
