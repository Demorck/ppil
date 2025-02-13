<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Vehicules;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class VehiculeModifFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Marque du véhicule',
                    'required' => true,
                ],
            ])
            ->add('modele', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Modèle du véhicule',
                    'readonly' => true,

                ],
            ])
            ->add('immatriculation', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Immatriculation du véhicule',
                ],
            ])
            ->add('annee', DateType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Année de mise en circulation',
                    'readonly' => true,

                ],
                'format' => 'yyyy-MM-dd',
                'years' => range(date('Y') - 50, date('Y')),
            ])
            ->add('nombrePlace', IntegerType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Nombre de places',
                    'readonly' => true,

                ],
            ])
            ->add('typeCarburant', ChoiceType::class, [
                'choices' => [
                    'Essence' => 'Essence',
                    'Diesel' => 'Diesel',
                    'Electrique' => 'Electrique',
                    'Hybride' => 'Hybride',
                ],
                'attr' => [
                    'readonly' => true,
                ],
                'required' => true,
            ])
            ->add('kilometrage', IntegerType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Kilométrage',
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (JPG, PNG)',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'accept' => 'image/png, image/jpeg',
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPG, PNG)',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicules::class,
        ]);
    }
}
