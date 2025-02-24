<?php

namespace App\Controller\Utilisateurs;

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

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword != null)
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));


            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_profile_page');
        }

        return $this->render('utilisateurs/profil_page.html.twig', [
            'title' => "Profile",
            'controller_name' => 'ProfilPageController',
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
