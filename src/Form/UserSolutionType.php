<?php

namespace App\Form;

use App\Entity\Exercise;
use App\Entity\User;
use App\Entity\UserSolution;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSolutionType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder->add('content', TextareaType::class, [
      'label' => 'Votre réponse',
      'attr' => [
        'placeholder' => 'Écrivez votre réponse ici...',
        'rows' => 16,
      ],
      'required' => true,
    ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => UserSolution::class,
    ]);
  }
}
