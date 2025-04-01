<?php

namespace App\Controller\Offres;

use App\Entity\Locations;
use App\Entity\Offres;
use App\Form\FormulaireCreerOffreType;


use App\Form\Offres\OffreType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Json;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

class OffreController extends AbstractController
{
    #[Route('/offre/{id}', name: 'app_offre')]
    public function index(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        if (!$this->getUser())
            $this->redirectToRoute('app_login');


        $offre = $entityManager->getRepository(Offres::class)->find($id);
        if (!$offre) {
            throw $this->createNotFoundException('Offre non trouvée.');
        }

        $user = $this->getUser();
        if ($user->getStatut() == 0)
            return $this->redirectToRoute('app_souscription_abonnement');

        $range = [];

        $hier = new DateTime();
        if ($offre->getDateDebut() <= $hier)
        {
            $range[] = [
                'dateDebut' => $offre->getDateDebut(),
                'dateFin' => $hier
            ];
        }

        foreach ($offre->getLocations() as $location) {
            $range[] = [
                'dateDebut' => $location->getDateDebut(),
                'dateFin' => $location->getDateFin(),
            ];
        }

        $location = new Locations();
        $form = $this->createForm(OffreType::class, $location, [
            'offre' => $offre,
            'existing_ranges' => $range,
        ]);
        $form->handleRequest($request);

        $days = 0;
        $days = $form->get('dateDebut')->getData() && $form->get('dateFin')->getData() ? $form->get('dateDebut')->getData()->diff($form->get('dateFin')->getData())->days : $days;
        $days+=1;
        if ($form->isSubmitted() && $form->isValid()) {
            if ($offre->getVehicule()->getProprietaire() === $user) {
                $this->addFlash('error', 'Vous ne pouvez pas louer votre propre véhicule.');
                return $this->redirectToRoute('app_offre', ['id' => $offre->getId()]);
            }

            $location->setPrix($offre->getPrix()*$days);
            $location->setStatut(0);
            $location->setOffre($offre);
            $location->setLocataire($user);
            $entityManager->persist($location);
            $entityManager->flush();

            $this->addFlash('success', 'Offre souscrite, plus qu\'à payer.');


            return $this->redirectToRoute('app_paiement', ['id' => $location->getId()]);
        }

        return $this->render('offres/page_offre.html.twig', [
            'form'  => $form->createView(),
            'controller_name' => 'OffreController',
            'offre' => $offre,
            'date_impossible' => json_encode($range),
        ]);
    }
}
