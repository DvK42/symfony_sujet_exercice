<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Exercise;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentAdminType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('content', null, [
        'label' => 'Contenu',
        'attr' => [
          'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
          'placeholder' => 'Saisissez votre commentaire',
        ],
      ])
      ->add('exercise', EntityType::class, [
        'class' => Exercise::class,
        'choice_label' => 'name',
        'label' => 'Exercice associé',
        'placeholder' => 'Sélectionnez un exercice',
        'attr' => [
          'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
        ],
      ])
      ->add('user', EntityType::class, [
        'class' => User::class,
        'choice_label' => 'email',
        'label' => 'Utilisateur',
        'placeholder' => 'Sélectionnez un utilisateur',
        'attr' => [
          'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
        ],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Comment::class,
    ]);
  }
}
