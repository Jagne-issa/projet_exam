<?php

// src/Form/ClientType.php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ pour le numéro de téléphone
            ->add('telephone', TelType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex: 773461882',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner un numéro de téléphone valide.']),
                    new NotNull(['message' => 'Le téléphone ne peut pas être vide']),
                    new Regex(
                        '/^(77|78|76)([0-9]{7})$/',
                        'Le numéro de téléphone doit être au format 77XXXXXX ou 78XXXXXX ou 76XXXXXX'
                    ),
                ],
                'help' => 'Le numéro doit commencer par 77, 78 ou 76.', // Help text example
            ])
            // Champ pour le nom de famille (surname)
            ->add('surname', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex: Dupont',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner un nom valide.']),
                ],
            ])
            // Champ pour le prénom
            ->add('prenom', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex: Jagne',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner un prénom valide.']),
                ],
            ])
            // Champ pour l'adresse
            ->add('adresse', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Adresse du client',
                ],
            ])
            // Bouton de soumission
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'csrf_protection' => true,
        ]);
    }
}
