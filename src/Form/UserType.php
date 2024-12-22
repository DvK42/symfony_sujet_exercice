<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('firstName', null, [
        'label' => 'Prénom',
        'attr' => [
          'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
        ],
      ])
      ->add('lastName', null, [
        'label' => 'Nom',
        'attr' => [
          'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
        ],
      ])
      ->add('studyLevel', ChoiceType::class, [
        'label' => 'Niveau d\'étude',
        'choices' => [
          'CP' => 'cp',
          'CE1' => 'ce1',
          'CE2' => 'ce2',
          'CM1' => 'cm1',
          'CM2' => 'cm2',
          '6e' => '6eme',
          '5e' => '5eme',
          '4e' => '4eme',
          '3e' => '3eme',
          'Seconde' => 'seconde',
          'Première' => 'premiere',
          'Terminale' => 'terminale',
        ],
        'placeholder' => 'Sélectionnez un niveau d\'étude',
        'attr' => [
          'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
        ],
      ])
      ->add('email', null, [
        'label' => 'Adresse email',
        'attr' => [
          'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
        ],
      ])
      ->add('roles', ChoiceType::class, [
        'label' => 'Rôles',
        'choices' => [
          'Utilisateur' => 'ROLE_USER',
          'Administrateur' => 'ROLE_ADMIN',
        ],
        'expanded' => true,
        'multiple' => true,
        'attr' => [
          'class' => 'block w-full gap-4',
        ],
      ])
      ->add('plainPassword', PasswordType::class, [
        'label' => 'Mot de passe',
        'mapped' => false, // Non lié directement à l'entité
        'required' => $options['is_new'], // Requis uniquement pour un nouvel utilisateur
        'attr' => [
          'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
          'autocomplete' => 'new-password',
        ],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
      'is_new' => false, // Détermine si le formulaire est pour un nouvel utilisateur ou non
    ]);
    $resolver->setAllowedTypes('is_new', 'bool');
  }
}
