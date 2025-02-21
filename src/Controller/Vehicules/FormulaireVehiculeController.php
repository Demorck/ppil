<?php

namespace App\Controller\Vehicules;

use App\Entity\Proprietaires;
use App\Entity\Vehicules;
use App\Form\VehiculeFormType;
use App\Form\VehiculeModifFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class FormulaireVehiculeController extends AbstractController
{
    #[Route('/vehicules/ajoutVehicule', name: 'app_formulaire_ajoutVehicule')]
    public function renseignerInfo(Request $request, EntityManagerInterface $entMan, SluggerInterface $slugger): Response
    {
         if ($this->getUser() === null) {
             return $this->redirectToRoute('app_login');
         }
         $currentUser = $this->getUser();

        $vehicule = new Vehicules();

        $form = $this->createForm(VehiculeFormType::class, $vehicule);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile)
            {
                $originalFileName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName); // enlève les caractères non ASCII pour le chemin du fichier
                $finalFileName = $safeFileName . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory')['vehicules'],
                        $finalFileName
                    );
                    $vehicule->setImageName($finalFileName);
                } catch (FileException $e)
                {
                    $form->addError(new FormError("L'image que vous avez téléchargé ne peut être sauvegardée"));
                    return $this->render('formulaire_vehicule/ajoutVehicule.html.twig', [
                        'controller_name'   => 'FormulaireVehiculeController',
                        'vehiculeForm'      => $form->createView(),
                        'vehicule'          => $vehicule,
                        'title'             => 'Nouveau Véhicule'
                    ]);
                }
            }

            $vehicule->setProprietaire($currentUser);
            $entMan->persist($vehicule);
            $entMan->flush();

            return $this->redirectToRoute('app_formulaire_vehicules');
        }

        return $this->render('formulaire_vehicule/ajoutVehicule.html.twig', [
            'controller_name'   => 'FormulaireVehiculeController',
            'vehiculeForm'      => $form->createView(),
            'vehicule'          => $vehicule,
            'title'             => 'Nouveau Véhicule'
        ]);
    }

    #[Route('/vehicules', name: 'app_formulaire_vehicules')]
    public function displayVehicules(Request $request, EntityManagerInterface $entMan): Response
    {
         if ($this->getUser() === null) {
             return $this->redirectToRoute('app_login');
         }

         $currentUser = $this->getUser();


        $vehicules = $entMan->getRepository(Vehicules::class)->findBy(['proprietaire' => $currentUser]);

        return $this->render('formulaire_vehicule/vehicules.html.twig', [
            'controller_name'   => 'MesVehiculesController',
            'vehicules'         => $vehicules,
            'title'             => 'Liste des véhicules',
        ]);
    }

    #[Route('/vehicules/{id}', name: 'app_formulaire_vehicule')]
    public function modifyVehicule(Request $request, SluggerInterface $slugger, EntityManagerInterface $entMan, $id): Response
    {
         if ($this->getUser() === null) {
             return $this->redirectToRoute('app_login');
         }

         $currentUser = $this->getUser();

        $vehicule = $entMan->getRepository(Vehicules::class)->findOneBy(['id' => $id, 'proprietaire' => $currentUser]);

        if ($vehicule === null) {
            return $this->redirectToRoute('app_formulaire_vehicules');
        }
        $form = $this->createForm(VehiculeModifFormType::class, $vehicule);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newImage = $form->get('image')->getData();
            if ($newImage != null) {
                $originalFileName = pathinfo($newImage->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName); // enlève les caractères non ASCII pour le chemin du fichier
                $finalFileName = $safeFileName . '-' . uniqid() . '.' . $newImage->guessExtension();

                try {
                    $newImage->move(
                        $this->getParameter('images_directory')['vehicules'],
                        $finalFileName
                    );

                    if ($vehicule->getImageName()) {
                        $oldImage = $this->getParameter('images_directory')['vehicules'] . '/' . $vehicule->getImageName();
                        if (file_exists($oldImage)) {
                            unlink($oldImage);
                        }
                    }

                    $vehicule->setImageName($finalFileName);
                } catch (FileException $e)
                {
                    $form->addError(new FormError("L'image que vous avez téléchargé ne peut être sauvegardée"));
                    return $this->render('formulaire_vehicule/gererVehicule.html.twig', [
                        'controller_name'   => 'MesVehiculesController',
                        'vehicule'          => $vehicule,
                        'title'             => 'Modification du véhicule',
                        'vehiculeGerer'      => $form->createView(),
                    ]);
                }


            }


            $entMan->persist($vehicule);
            $entMan->flush();

            return $this->redirectToRoute('app_formulaire_vehicules');
        }

        return $this->render('formulaire_vehicule/gererVehicule.html.twig', [
            'controller_name'   => 'MesVehiculesController',
            'vehicule'          => $vehicule,
            'title'             => 'Modification du véhicule',
            'vehiculeGerer'      => $form->createView(),

        ]);
    }

    #[Route('/vehicules/{id}/delete', name: 'app_formulairevehicule_deletevehicule')]
    public function deleteVehicule(Request $request, EntityManagerInterface $entMan, $id): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $currentUser = $this->getUser();

        $vehicule = $entMan->getRepository(Vehicules::class)->findOneBy(['id' => $id, 'proprietaire' => $currentUser]);

        $entMan->remove($vehicule);
        $entMan->flush();

        return $this->redirectToRoute('app_formulaire_vehicules');
    }

}
