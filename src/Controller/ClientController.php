<?php

namespace App\Controller;

use App\Form\ClientType;
use App\Form\SearchClientType;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface; // Assurez-vous d'importer le bon namespace pour la pagination
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/clients', name: 'app_home')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        // Formulaire de recherche
        $formSearch = $this->createForm(SearchClientType::class);
        $formSearch->handleRequest($request);

        $queryBuilder = $this->entityManager->getRepository(Client::class)->createQueryBuilder('c');

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $data = $formSearch->getData();
            if ($data['telephone']) { // Correction du champ de recherche
                $queryBuilder->andWhere('c.telephone LIKE :telephone')
                             ->setParameter('telephone', '%' . $data['telephone'] . '%');
            }
            if ($data['surname']) {
                $queryBuilder->andWhere('c.surname LIKE :surname')
                             ->setParameter('surname', '%' . $data['surname'] . '%');
            }
        }

        // Pagination
        $clients = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10 // nombre d'éléments par page
        );

        // Récupération des variables de pagination
        $currentPage = $clients->getCurrentPageNumber(); // Page actuelle
        $itemsPerPage = $clients->getItemNumberPerPage(); // Nombre d'éléments par page
        $totalClients = $clients->getTotalItemCount(); // Total des clients
        $totalPages = $totalClients > 0 ? ceil($totalClients / $itemsPerPage) : 1; // Nombre total de pages

        // Formulaire de création de client
        $client = new Client();
        $formCreate = $this->createForm(ClientType::class, $client);
        $formCreate->handleRequest($request);

        if ($formCreate->isSubmitted() && $formCreate->isValid()) {
            $this->entityManager->persist($client);
            $this->entityManager->flush();

            $this->addFlash('info', 'Client créé avec succès.');
            return $this->redirectToRoute('app_home');
        }

        // Rendu du template avec les variables
        return $this->render('client/index.html.twig', [
            'clients' => $clients,
            'formSearch' => $formSearch->createView(),
            'formCreate' => $formCreate->createView(),
            'currentPage' => $currentPage ?? 1,
            'itemsPerPage' => $itemsPerPage ?? 10,
            'totalClients' => $totalClients ?? 0,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/client/edit/{id}', name: 'clients.edit')]
    public function edit(Client $client, Request $request): Response
    {
        $formEdit = $this->createForm(ClientType::class, $client);
        $formEdit->handleRequest($request);

        if ($formEdit->isSubmitted() && $formEdit->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('info', 'Client modifié avec succès.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('client/edit.html.twig', [
            'formEdit' => $formEdit->createView(),
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
            $this->addFlash('error', 'Une erreur est survenue lors de la suppression du client.');
        }

        return $this->redirectToRoute('app_home');
    }
}
