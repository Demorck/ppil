<?php

declare(strict_types=1);

namespace App\Form\Utilisateurs;

use App\Entity\Abonnements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbonnementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Journalier' => 0,
                    'Hebdomadaire' => 1,
                    'Mensuel' => 2,
                    'Annuel' => 3,
                ],
                'label' => 'Choisissez votre abonnement',
                'expanded' => true,
                'multiple' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Abonnements::class,
        ]);
    }
}
