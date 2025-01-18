<?php

namespace App\Form;

use App\Entity\Proprietaires;
use App\Entity\Vehicules;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class VehiculeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Marque du véhicule',
                ],
            ])
            ->add('modele', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Modèle du véhicule',
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
                ],
                'format' => 'yyyy-MM-dd',
                'years' => range(date('Y') - 50, date('Y')),
            ])
            ->add('nombrePlace', IntegerType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Nombre de places',
                ],
            ])
            ->add('typeCarburant', ChoiceType::class, [
                'choices' => [
                    'Essence' => 'Essence',
                    'Diesel' => 'Diesel',
                    'Electrique' => 'Electrique',
                    'Hybride' => 'Hybride',
                ],
                'required' => true,
            ])
            ->add('kilometrage', IntegerType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Kilométrage',
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
