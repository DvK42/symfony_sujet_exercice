<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('password', RepeatedType::class, [
        'type' => PasswordType::class,
        'first_options' => [
          'label' => 'Mot de passe',
        ],
        'second_options' => [
          'label' => 'Confirmez le mot de passe',
        ],
        'invalid_message' => 'Les mots de passe doivent correspondre.',
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([]);
  }
}