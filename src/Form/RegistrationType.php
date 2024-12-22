<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Unique;

class RegistrationType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('firstName', TextType::class, [
        'label' => 'Prénom',
      ])
      ->add('lastName', TextType::class, [
        'label' => 'Nom',
      ])
      ->add('studyLevel', ChoiceType::class, [
        'choices' => [
          'CP' => 'cp',
          'CE1' => 'ce1',
          'CE2' => 'ce2',
          'CM1' => 'cm1',
          'CM2' => 'cm2',
          '6ème' => '6eme',
          '5ème' => '5eme',
          '4ème' => '4eme',
          '3ème' => '3eme',
          'Seconde' => 'seconde',
          'Première' => 'premiere',
          'Terminale' => 'terminale',
        ],
        'placeholder' => '',
        'label' => 'Niveau d\'étude',
        'attr' => [
          'class' => 'form-control',
        ],
      ])
      ->add('email', EmailType::class, [
        'label' => 'Adresse email',
        'required' => true,
        'attr' => [
          'class' => 'form-control',
        ],
        'constraints' => [
          new \Symfony\Component\Validator\Constraints\NotBlank([
            'message' => 'Veuillez saisir une adresse email.',
          ]),
          new \Symfony\Component\Validator\Constraints\Email([
            'message' => 'L\'adresse email "{{ value }}" n\'est pas valide.',
          ]),
        ],
      ])
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
    $resolver->setDefaults([
      'data_class' => User::class,
    ]);
  }
}
