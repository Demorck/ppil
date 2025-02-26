<?php

namespace App\Form;

use App\Entity\Offres;
use App\Entity\Vehicules;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class FormulaireCreerOffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
         $user = $options['user'];
         $userID = $user->getId();
    
        $builder
            ->add('dateDebut', null, [
                'widget' => 'single_text',
            ])
            ->add('dateFin', null, [
                'widget' => 'single_text',
            ])
            ->add('prix', MoneyType::class)
            ->add('vehicule', EntityType::class, [
                'class' => Vehicules::class,
                'query_builder' => function (EntityRepository $er) use ($userID) {
                    return $er->createQueryBuilder('vehicule')
                              ->where('vehicule.proprietaire = :userId')
                              ->setParameter('userId', $userID);
                },
                'choice_label' => function (Vehicules $vehicule) {
                    return $vehicule->getTitre();
                },
                'choice_attr' => function (Vehicules $vehicule) {
                    return [
                        'data-chemin-image' => $vehicule->getImageName(),
                        'cheminImage' => "images/default.jpg",
                        'data-modele' => $vehicule->getModele(),
                        'data-plaque' => $vehicule->getImmatriculation(),
                        'data-marque' => $vehicule->getMarque(),
                    ];
                },
                'placeholder' => 'vehicule',
            ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offres::class,
            'user' => null,
            'csrf_protection' => false
        ]);
    }
}
