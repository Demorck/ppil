<?php

namespace App\Controller\Utilisateurs;

use App\Entity\Abonnements;
use App\Form\Utilisateurs\ModificationProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ProfilPageController extends AbstractController
{
    #[Route('/profil', name: 'app_profile_page')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();

        if ($user == null)
            return $this->redirectToRoute('app_login');

        $form = $this->createForm(ModificationProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $plainPassword = $form->get('password')->getData();
                if ($plainPassword != null)
                    $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_profile_page');
            } else {
                $entityManager->refresh($user);
            }
        }

        $actifs = $entityManager->getRepository(Abonnements::class)->findBy([
            'utilisateur' => $this->getUser(),
            'statut' => 1
        ]);
        if ($actifs != null)
        {
            $now = new \DateTime();
            $actifs = array_filter($actifs, function (Abonnements $abonnement) use ($now) {
                return $abonnement->getDateFin() >= $now;
            });

            $dureeRestante = 0;
            foreach ($actifs as $abonnement) {
                $diff = (new \DateTime())->diff($abonnement->getDateFin());
                $dureeRestante += max(0, $diff->days);
            }

            $dateFin = null;
            if ($dureeRestante > 0) {
                $dateFin = (new \DateTime())->add(new \DateInterval('P' . $dureeRestante . 'D'));
            }
        }

        return $this->render('utilisateurs/profil_page.html.twig', [
            'title' => "Profile",
            'controller_name' => 'ProfilPageController',
            'user' => $user,
            'form' => $form->createView(),
            'vehicules' => $user->getVehicules(),
            'locations' => $user->getLocations(),
            'abonnement' => $dateFin,
            'duree' => $dureeRestante,
        ]);
    }
}
