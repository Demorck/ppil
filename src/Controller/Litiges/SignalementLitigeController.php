<?php

declare(strict_types=1);

namespace App\Controller\Litiges;

use App\Entity\Litiges;
use App\Entity\Locations;
use App\Entity\Utilisateurs;
use App\Form\Litiges\SignalementLitigeType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\Routing\Attribute\Route;

class SignalementLitigeController extends AbstractController
{
    #[Route('/litige/{id}', name: 'app_signalement_litige')]
    public function index(EntityManagerInterface $entityManager, Request $request, $id): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        $location = $entityManager->getRepository(Locations::class)->findOneBy(['id' => $id]);

        if (!$location || $location->getLitiges())
            return $this->redirectToRoute('app_abonnement');

        $litige = new Litiges();
        $form = $this->createForm(SignalementLitigeType::class, $litige);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $litige->setStatut(0);
            $litige->setLocation($location);

            $juristes = $entityManager->getRepository(Utilisateurs::class)
                ->createQueryBuilder('u')
                ->where('u.roles LIKE :role')
                ->setParameter('role', '%ROLE_JURISTE%')
                ->getQuery()
                ->getResult();

            shuffle($juristes);
            $juristeLitige = null;

            $proprietaire = $location->getOffre()->getVehicule()->getProprietaire();
            $locataire = $this->getUser();
            foreach ($juristes as $juriste)
            {
                if ($juriste != $proprietaire && $juriste != $locataire)
                {
                    $juristeLitige = $juriste;
                    break;
                }
            }

            if (!$juristeLitige)
                throw new ServiceUnavailableHttpException(null, "Aucun juriste disponible, veuillez contacter l'administrateur");

            $litige->setJuriste($juristeLitige);

            $entityManager->persist($litige);
            $entityManager->flush();

            return $this->redirectToRoute('app_home_page');
        }


        return $this->render('litiges/ajouter_litige.html.twig', [
            'form' => $form,
            'location' => $location,
        ]);
    }
}
