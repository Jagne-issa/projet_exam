<?php

// src/Controller/DebtController.php

namespace App\Controller;

use App\Entity\Dette; // Utilisez 'Dette' ici
use App\Form\DebtType; // Assurez-vous que le nom du formulaire est correct
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    }

    #[Route('/debt/new', name: 'debt_new')]
    public function new(Request $request): Response
    {
        $debt = new Dette(); // Utilisez 'Dette' ici
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
    $statusFilter = $request->query->get('status', []);
    
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

    

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant')
            ->add('montantVerser')
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => function (Client $client) {
                    return (string) $client; // Utilisation de la méthode __toString()
                },
            ]);
    }
}
