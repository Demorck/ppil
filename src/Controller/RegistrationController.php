<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Locataires;
use App\Entity\Proprietaires;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Utilisateurs();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();


            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $user -> setStatut(0);

            $user -> setRoles($form->get('roles')->getData());

            if(in_array('ROLE_LOCATAIRE', $user->getRoles())){
                $locataire = new Locataires();
                $locataire->setEmail($user->getEmail());
                $locataire->setPrenom($user->getPrenom());
                $locataire->setNom($user->getNom());
                $locataire->setPassword($user->getPassword());
                $locataire->setRoles($user->getRoles());
                $locataire->setStatut($user->getStatut());
                $entityManager->persist($locataire);

            }
            if(in_array('ROLE_PROPRIETAIRE', $user->getRoles())){
                $proprietaire = new Proprietaires();
                $proprietaire->setEmail($user->getEmail());
                $proprietaire->setPrenom($user->getPrenom());
                $proprietaire->setNom($user->getNom());
                $proprietaire->setPassword($user->getPassword());
                $proprietaire->setRoles($user->getRoles());
                $proprietaire->setStatut($user->getStatut());
                $entityManager->persist($proprietaire);
            }



            try{
                $entityManager->flush();
            }catch(\Doctrine\DBAL\Exception $e){
                dump($e->getMessage());
            }
                

            // do anything else you need here, like send an email

            

            return $this->redirectToRoute('app_home_page');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
