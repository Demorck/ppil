<?php

namespace App\Form\Utilisateurs;

use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\Validator\Constraints\Regex;

class ModificationProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true
            ])
            ->add('prenom', TextType::class, [
                'trim' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner votre prénom.'
                    ]),
                    new Regex([
                        'pattern' => '/^[[:alpha:]]+[[:alpha:] -]*[[:alpha:]]+$/u',
                        'message' => 'Le prénom doit faire au moins 2 caractères et ne doit contenir que des lettres, des tirets ou espaces. (mais pas au début ni à la fin)'
                    ])
                ]
            ])
            ->add('nom', TextType::class, [
                'trim' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner votre nom.'
                    ]),
                    new Regex([
                        'pattern' => '/^[[:alpha:]]+[[:alpha:] -]*[[:alpha:]]+$/u',
                        'message' => 'Le nom doit faire au moins 2 caractères et ne doit contenir que des lettres, des tirets ou espaces. (mais pas au début ni à la fin)'
                    ])
                ]
            ])
            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'required' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new PasswordStrength([
                        'minScore' => PasswordStrength::STRENGTH_STRONG,
                        'message' => 'Le mot de passe est trop facile, veuillez mettre un mot de passe plus compliqué.'
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class
        ]);
    }
}
