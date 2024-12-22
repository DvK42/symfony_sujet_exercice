<?php

namespace App\Form;

use App\Entity\Chapiter;
use App\Entity\Level;
use App\Entity\Subject;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChapiterType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name', null, [
        'label' => 'Nom du chapitre',
        'attr' => [
          'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
        ],
      ])
      ->add('slug', null, [
        'label' => 'Slug',
        'attr' => [
          'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
        ],
      ])
      ->add('subject', EntityType::class, [
        'class' => Subject::class,
        'choice_label' => 'name',
        'label' => 'Matière associée',
        'attr' => [
          'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
        ],
        'placeholder' => '',
      ])
      ->add('level', EntityType::class, [
        'class' => Level::class,
        'choice_label' => 'name',
        'label' => 'Niveau associé',
        'attr' => [
          'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
        ],
        'placeholder' => '',
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Chapiter::class,
    ]);
  }
}
