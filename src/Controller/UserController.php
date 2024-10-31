<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/register", name="user_register")
     */
    public function register(Request $request): Response
    {
        // Récupérez les données du formulaire (email, mot de passe, etc.)
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        // Créez l'utilisateur via le service
        $user = $this->userService->createUser($email, $password);

        // Retournez une réponse appropriée
        return $this->json(['message' => 'User created successfully.']);
    }
}
