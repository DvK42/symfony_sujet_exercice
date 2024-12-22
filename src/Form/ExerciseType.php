<?php

namespace App\Form;

use App\Entity\Chapiter;
use App\Entity\Exercise;
use App\Entity\Level;
use App\Entity\Subject;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExerciseType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name')
      ->add('content')
      ->add('solution')
      ->add('slug')
      ->add('author', EntityType::class, [
        'class' => User::class,
        'choice_label' => 'email',
      ])
      ->add('chapiter', EntityType::class, [
        'class' => Chapiter::class,
        'choice_label' => 'name',
      ])
      ->add('subject', EntityType::class, [
        'class' => Subject::class,
        'choice_label' => 'name',
      ])
      ->add('level', EntityType::class, [
        'class' => Level::class,
        'choice_label' => 'name',
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Exercise::class,
    ]);
  }
}
