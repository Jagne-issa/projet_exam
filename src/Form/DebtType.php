<?php

// src/Form/DebtType.php

namespace App\Form;

use App\Entity\Dette;
use App\Entity\Client;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class DebtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant', NumberType::class)
            ->add('montantVerser', NumberType::class)
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => function (Client $client) {
                    return (string) $client; // Utilisation de __toString()
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dette::class,
        ]);
    }
}
