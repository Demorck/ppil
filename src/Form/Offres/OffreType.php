<?php

declare(strict_types=1);

namespace App\Form\Offres;

use App\Entity\Locations;
use App\Entity\Offres;
use App\Validator\NoDateOverlap;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $existingRanges = $options['existing_ranges'] ?? [];

        $builder
            ->add('dateDebut', DateTimeType::class, [
                'required' => true,
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => new \DateTime(),
                        'message' => 'La date de début doit être aujourd\'hui ou plus tard.'
                    ])
                ]
            ])
            ->add('dateFin', DateTimeType::class, [
                'required' => true,
                'constraints' => [
                    new GreaterThan([
                        'propertyPath' => 'parent.all[dateDebut].data',
                        'message' => 'La date de fin doit être après la date de début.'
                    ]),
                    new NoDateOverlap([
                        'existingRanges' => $existingRanges
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Locations::class,
            'offre' => null,
            'existingRanges' => null,
        ]);
    }
}
