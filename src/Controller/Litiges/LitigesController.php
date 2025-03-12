<?php

namespace App\Controller\Litiges;

use App\Entity\Litiges;
use App\Entity\Locations;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LitigesController extends AbstractController
{
    #[Route('/location/{id1}/litiges/{id}', name: 'app_litiges')]
    public function index(Request $request, EntityManagerInterface $entMan, $id, $id1): Response
    {
        if ($this->getUser() === null) {
                return $this->redirectToRoute('app_login');
        }

        $currentUser = $this->getUser();
        $userid = $currentUser->getId();
        $location_id = $entMan->getRepository(Locations::class)->find($id1);

        $litige = $entMan->getRepository(Litiges::class)->findOneBy(['id' => $location_id]);

        if ($litige === null) {
            return $this->redirectToRoute('app_home_page');
        }

        $litigeText = $litige->getDescription();

        $litigeStatus = $litige->getStatut();
        if ($litigeStatus === 0) {
            $litigeStatus = 'En cours';
        } else {
            $litigeStatus = 'Résolu';
        }

        $litigeJurist = $litige->getJuriste();

        $litigeLocation = $litige->getLocation();

        $litigeId = $litige->getId();

        return $this->render('litiges/index.html.twig', [
            'controller_name' => 'LitigesController',
            'litigeText' => $litigeText,
            'litigeStatus' => $litigeStatus,
            'litigeJurist' => $litigeJurist,
            'litigeLocation' => $litigeLocation,
            'litigeId' => $litigeId,
        ]);
    }
}
