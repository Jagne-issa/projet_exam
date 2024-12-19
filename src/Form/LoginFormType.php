<?php

// src/Form/LoginFormType.php
namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Dans votre FormType (par exemple, LoginFormType)
$builder
->add('_username', EmailType::class, [
    'label' => 'Nom d\'utilisateur ou Email',
    'attr' => ['placeholder' => 'Nom d\'utilisateur ou Email'],
    'constraints' => [new NotBlank(['message' => 'Veuillez saisir votre email.'])],
])
->add('_password', PasswordType::class, [
    'label' => 'Mot de passe',
    'attr' => ['placeholder' => 'Mot de passe'],
    'constraints' => [new NotBlank(['message' => 'Veuillez saisir votre mot de passe.'])],
]);

    }
}
