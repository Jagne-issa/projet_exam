<?php

// src/Controller/DebtController.php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Dette;
use App\Form\DebtType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DebtController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        // $this->denyAccessUnlessGranted('EDIT', $subject);

    }

    #[Route('/debt/new', name: 'debt_new')]
    public function new(Request $request): Response
    {
        $debt = new Dette();
        $form = $this->createForm(DebtType::class, $debt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($debt);
            $this->entityManager->flush();

            $this->addFlash('info', 'Client créé avec succès.');
            return $this->redirectToRoute('debt_new');
        }

        return $this->render('debt/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/debt', name: 'debt_index')]
    public function index(Request $request): Response
    {
        $statusFilter = $request->query->all('status', []);

        if (!is_array($statusFilter)) {
            $statusFilter = [];
        }
    
        $queryBuilder = $this->entityManager->getRepository(Dette::class)->createQueryBuilder('d');
    
        if (in_array('active', $statusFilter)) {
            $queryBuilder->andWhere('d.status = :active')
                         ->setParameter('active', 'active');
        }
        
        if (in_array('paid', $statusFilter)) {
            $queryBuilder->orWhere('d.status = :paid')
                         ->setParameter('paid', 'paid');
        }
    
        $debts = $queryBuilder->getQuery()->getResult();
    
        return $this->render('debt/index.html.twig', [
            'debts' => $debts,
        ]);
    }
}
