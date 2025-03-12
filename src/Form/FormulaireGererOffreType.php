<?php

namespace App\Form;

use App\Entity\Offres;
use App\Entity\Vehicules;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormulaireGererOffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', null, [
                'widget' => 'single_text',
            ])
            ->add('dateFin', null, [
                'widget' => 'single_text',
            ])
            ->add('prix')
            ->add('statut')
            ->add('vehicule', EntityType::class, [
                'disabled' => true,
                'class' => Vehicules::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offres::class,
        ]);
    }
}
