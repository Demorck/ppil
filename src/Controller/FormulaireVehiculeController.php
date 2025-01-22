<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Vehicules;
use App\Form\VehiculeFormType;
use App\Entity\Proprietaires;
use function Symfony\Component\Clock\now;

class FormulaireVehiculeController extends AbstractController
{
    #[Route('/vehicule/ajoutVehicule', name: 'app_formulaire_ajoutVehicule')]
    public function renseignerInfo(Request $request, EntityManagerInterface $entMan): Response
    {
         if ($this->getUser() === null) {
             return $this->redirectToRoute('app_login');
         }
         $currentUser = $this->getUser();

        $vehicule = new Vehicules();

        $form = $this->createForm(VehiculeFormType::class, $vehicule);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
    public function display_vehicules(Request $request, EntityManagerInterface $entMan): Response
    {
         if ($this->getUser() === null) {
             return $this->redirectToRoute('app_login');
         }

         $currentUser = $this->getUser();


        $vehicules = $entMan->getRepository(Vehicules::class)->findAll();

        return $this->render('formulaire_vehicule/vehicules.html.twig', [
            'controller_name'   => 'MesVehiculesController',
            'vehicules'         => $vehicules,
            'title'             => 'Liste des véhicules'
        ]);
    }
}
