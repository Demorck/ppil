<?php

namespace App\Controller;

use App\Form\user\ModificationProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ProfilePageController extends AbstractController
{
    #[Route('/profile', name: 'app_profile_page')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();
        $form = $this->createForm(ModificationProfilType::class, $user, array('method' => 'PUT'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword != null)
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));


            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_home_page');
        }

        return $this->render('profile_page/index.html.twig', [
            'title' => "Profile",
            'controller_name' => 'ProfilePageController',
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
