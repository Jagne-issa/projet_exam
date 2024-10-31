<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository; 
use App\Form\SearchClientType;
use App\Repository\DetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private DetteRepository $detteRepository;

    public function __construct(EntityManagerInterface $entityManager, DetteRepository $detteRepository)
    {
        $this->entityManager = $entityManager;
        $this->detteRepository = $detteRepository;
        // $this->denyAccessUnlessGranted('EDIT', $subject);

    }

    #[Route('/clients', name: 'app_home', requirements: ['page' => '\d+'])]
    public function index(Request $request, ClientRepository $clientRepository, PaginatorInterface $paginator): Response
    {
        // Formulaire de recherche
        $formSearch = $this->createForm(SearchClientType::class);
        $formSearch->handleRequest($request);

        $criteria = $formSearch->isSubmitted() && $formSearch->isValid()
            ? $formSearch->getData()
            : [];

        // Récupérer la requête pour les clients avec critères de recherche
        $query = $clientRepository->findAllQuery($criteria);

        // Pagination
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Numéro de page par défaut 1
            10 // Nombre d'éléments par page
        );

        // Formulaire pour créer un nouveau client
        $client = new Client();
        $formCreate = $this->createForm(ClientType::class, $client);
        $formCreate->handleRequest($request);

        if ($formCreate->isSubmitted() && $formCreate->isValid()) {
            $this->entityManager->persist($client);
            $this->entityManager->flush();
            $this->addFlash('success', 'Client créé avec succès!');

            return $this->redirectToRoute('app_home'); // Rediriger vers la liste des clients
        }

        return $this->render('client/index.html.twig', [
            'clients' => $pagination, // Clients paginés
            'formSearch' => $formSearch->createView(),
            'formCreate' => $formCreate->createView(),
        ]);
    }

    #[Route('/client/edit/{id}', name: 'clients.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client): Response
    {
        // Créer le formulaire pour éditer le client
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les modifications
            $this->entityManager->flush();
            $this->addFlash('success', 'Client modifié avec succès!');

            return $this->redirectToRoute('app_home'); // Rediriger vers la liste des clients
        }

        return $this->render('client/edit.html.twig', [
            'form' => $form->createView(),
            'client' => $client,
        ]);
    }

    #[Route('/client/delete/{id}', name: 'clients.delete', methods: ['POST'])]
    public function delete(Client $client): Response
    {
        try {
            $this->entityManager->remove($client);
            $this->entityManager->flush();
            $this->addFlash('info', 'Client supprimé avec succès.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de la suppression du client : ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_home');
    }

    #[Route('/client/debts/{id}', name: 'client.debts')]
    public function showDebts(Client $client): Response
    {
        $debts = $this->detteRepository->findBy(['client' => $client]);
        return $this->render('client/debts.html.twig', [
            'client' => $client,
            'debts' => $debts,
        ]);
    }
}
