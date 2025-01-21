<?php

namespace App\Form;

use App\Entity\Offres;
use App\Entity\Vehicules;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class FormulaireCreerOffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $userId = $options['user']['id'];
        $userId = 1; // Utilisateur 1 pour exemple
    
        $builder
            ->add('dateDebut', null, [
                'widget' => 'single_text',
            ])
            ->add('dateFin', null, [
                'widget' => 'single_text',
            ])
            ->add('prix')
            ->add('vehicule', EntityType::class, [
                'class' => Vehicules::class,
                'query_builder' => function (EntityRepository $er) use ($userId) {
                    return $er->createQueryBuilder('vehicule')
                              ->where('vehicule.proprietaire = :userId')
                              ->setParameter('userId', $userId);
                },
                'choice_label' => 'id',
            ])
        ;
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offres::class,
            'user' => null,
        ]);
    }
}
