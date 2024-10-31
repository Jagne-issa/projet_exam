<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager; // Stocker l'EntityManager pour l'utiliser dans d'autres méthodes
        // $this->denyAccessUnlessGranted('EDIT', $subject);

    }

    #[Route('/articles', name: 'article_list')]
    public function index(Request $request, ArticleRepository $articleRepository): Response
    {
        
               
        // Récupérer tous les articles
        $articles = $articleRepository->findAll();

        // Définir le nombre d'articles par page
        $itemsPerPage = 10; // Ajustez ce nombre selon vos besoins
        $currentPage = $request->query->getInt('page', 1); // Page actuelle

        // Calculer le nombre total d'articles
        $totalItems = count($articles);
    
        // Calculer le nombre total de pages
        $totalPages = ceil($totalItems / $itemsPerPage);

        // Récupérer les articles pour la page actuelle
        $offset = ($currentPage - 1) * $itemsPerPage;
        $articlesForCurrentPage = array_slice($articles, $offset, $itemsPerPage);
    
        // Créer un nouvel article
        $article = new Article();
        $formArticle = $this->createForm(ArticleType::class, $article);
        $formArticle->handleRequest($request);
    
        if ($formArticle->isSubmitted() && $formArticle->isValid()) {
            // Enregistrer l'article dans la base de données
            $this->entityManager->persist($article); // Utilisation de l'entityManager injecté
            $this->entityManager->flush();
    
            // Message de succès
            $this->addFlash('success', 'Article créé avec succès !');
    
            // Rediriger vers la liste des articles
            return $this->redirectToRoute('article_list');
        }
    
        return $this->render('article/index.html.twig', [
            'articles' => $articlesForCurrentPage,
            'articles' => $articles, // Utilisez tous les articles ici
            'formArticle' => $formArticle->createView(),
            'currentPage' => $currentPage,
            'totalPages' => $totalPages, 

        ]);
    }

    #[Route('/article/new', name: 'article_new')]
    public function new(Request $request): Response
    {
        $article = new Article();
        $formArticle = $this->createForm(ArticleType::class, $article);
        $formArticle->handleRequest($request);
    
        if ($formArticle->isSubmitted() && $formArticle->isValid()) {
            $this->entityManager->persist($article);
            $this->entityManager->flush();
    
            $this->addFlash('success', 'Article créé avec succès.');
            return $this->redirectToRoute('article_list');
        }
    
        // Afficher les erreurs de validation si le formulaire n'est pas valide
        if ($formArticle->isSubmitted() && !$formArticle->isValid()) {
            $this->addFlash('error', 'Des erreurs ont été détectées dans le formulaire.');
        }
    
        return $this->render('article/new.html.twig', [
            'formArticle' => $formArticle->createView(),
        ]);
    }

    #[Route('/article/{id}/edit', name: 'article_edit')]
    public function edit(Request $request, Article $article): Response
    {
        $formArticle = $this->createForm(ArticleType::class, $article);
        $formArticle->handleRequest($request);

        if ($formArticle->isSubmitted() && $formArticle->isValid()) {
            $this->entityManager->flush(); // Mise à jour des données

            $this->addFlash('success', 'Article mis à jour avec succès.');
            return $this->redirectToRoute('article_list'); // Redirection vers la liste des articles
        }

        return $this->render('article/edit.html.twig', [
            'formArticle' => $formArticle->createView(),
            'article' => $article,
        ]);
    }

    #[Route('/article/{id}/delete', name: 'article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($article);
            $this->entityManager->flush();

            $this->addFlash('success', 'Article supprimé avec succès.');
        }

        return $this->redirectToRoute('article_list'); // Redirection vers la liste des articles
    }
}
