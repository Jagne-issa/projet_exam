<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer l'utilisateur
            $entityManager->persist($user);
            $entityManager->flush();

            // Envoi de l'email de confirmation
            $email = (new Email())
                ->from('jagneissa05@gmail.com')
                ->to($user->getEmail())
                ->subject('Bienvenue sur notre site!')
                ->html('<p>Votre compte a bien été créé avec succès. Bienvenue parmi nous !</p>');

            $mailer->send($email);

            // Redirection vers la page de compte
            return $this->redirectToRoute('app_home');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
