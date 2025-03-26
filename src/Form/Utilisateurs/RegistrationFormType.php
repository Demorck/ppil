<?php

namespace App\Form\Utilisateurs;

use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('prenom', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner votre prénom.'
                    ]),
                    new Regex([
                        'pattern' => '/^[[:alpha:]]+[[:alpha:] -]*[[:alpha:]]+$/u',
                        'message' => 'Le prénom doit faire au moins 2 caractères et ne doit contenir que des lettres, des tirets ou espaces. (mais pas en ni à la fin)'
                    ])
                ]
            ])
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner votre nom.'
                    ]),
                    new Regex([
                        'pattern' => '/^[[:alpha:]]+[[:alpha:] -]*[[:alpha:]]+$/u',
                        'message' => 'Le nom doit faire au moins 2 caractères et ne doit contenir que des lettres, des tirets ou espaces. (mais pas en ni à la fin)'
                    ])
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => "Veuillez accepter nos conditions d'utilisation.",
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions pour continuer.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => "Mot de passe",
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{10,}$/',
                        'message' => "Le mot de passe est trop facile. 
                        Il doit contenir au moins un chiffre, une lettre minuscule et majuscule."
                    ])
                ],
            ])
            ->add('roles', ChoiceType::class,[
                'label' => 'Rôles :',
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'Locataire' => 'ROLE_LOCATAIRE',
                    'Propriétaire' => 'ROLE_PROPRIETAIRE',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'form_register',
        ]);
    }
}
