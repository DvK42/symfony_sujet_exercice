<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ProfileEditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('firstName', TextType::class, [
        'label' => 'Prénom',
        'attr' => [
          'class' => 'block w-full text-sm font-medium text-gray-700 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
          'placeholder' => 'Entrez votre prénom',
        ],
      ])
      ->add('lastName', TextType::class, [
        'label' => 'Nom',
        'attr' => [
          'class' => 'block w-full text-sm font-medium text-gray-700 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
          'placeholder' => 'Entrez votre nom',
        ],
      ])
      ->add('email', EmailType::class, [
        'label' => 'Adresse email',
        'attr' => [
          'class' => 'block w-full text-sm font-medium text-gray-700 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
          'placeholder' => 'Entrez votre email',
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
          'class' => 'block w-full text-sm font-medium text-gray-700 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
        ],
      ])
      ->add('plainPassword', RepeatedType::class, [
        'type' => PasswordType::class,
        'mapped' => false,
        'required' => false,
        'first_options' => [
          'label' => 'Nouveau mot de passe',
          'attr' => [
            'class' => 'block w-full text-sm font-medium text-gray-700 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
          ],
        ],
        'second_options' => [
          'label' => 'Confirmer le mot de passe',
          'attr' => [
            'class' => 'block w-full text-sm font-medium text-gray-700 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
          ],
        ],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
    ]);
  }
}
