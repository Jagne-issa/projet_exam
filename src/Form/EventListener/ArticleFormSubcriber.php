<?php

// src/Form/EventListener/ArticleFormSubcriber.php

namespace App\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubcriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class ArticleFormSubcriber implements EventSubcriberInterface
{
    public static function getSubcribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
            FormEvents::POST_SUBMIT => 'onPostSubmit',
        ];
    }

    public function onPreSubmit(FormEvent $event)
    {
        // Manipulez les données soumises avant qu'elles ne soient validées
        $data = $event->getData();
        // Exemple : Vérifiez ou modifiez des champs ici
    }

    public function onPostSubmit(FormEvent $event)
    {
        // Action après la soumission du formulaire
        $form = $event->getForm();
        // Exemple : Ajouter des erreurs personnalisées si nécessaire
    }
}
