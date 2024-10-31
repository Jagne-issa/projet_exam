<?php 

namespace App\Controller;

use App\Event\FileUploadEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FileUploadController extends AbstractController
{
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function upload(Request $request): Response
    {
        // Supposons que vous ayez un fichier téléchargé via le formulaire
        $uploadedFile = $request->files->get('file'); // Changez 'file' selon le nom de votre champ de formulaire

        if ($uploadedFile) {
            // Créez l'événement
            $event = new FileUploadEvent($uploadedFile);
            // Déclenchez l'événement
            $this->eventDispatcher->dispatch($event, FileUploadEvent::NAME);

            // Logique supplémentaire après le téléchargement, par exemple, rediriger l'utilisateur
            return $this->redirectToRoute('upload_success');
        }

        return $this->render('upload_form.html.twig');
    }
}
