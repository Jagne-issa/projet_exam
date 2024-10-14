<?php

// src/Controller/ClientController.php

namespace App\Controller;

use App\DTO\ClientSearch;
use App\Entity\Client; // Assurez-vous d'importer l'entité Client
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        // Définissez une variable pour contrôler l'affichage du formulaire
        $showSearchForm = false;

        // Si une recherche est effectuée, mettez à jour la variable
        if ($request->isMethod('POST')) {
            $showSearchForm = true;
            // ... logique de recherche ici
        }

        return $this->render('client/index.html.twig', [
            'showSearchForm' => $showSearchForm,
            // ... autres variables à transmettre à la vue
        ]);
    }
}
