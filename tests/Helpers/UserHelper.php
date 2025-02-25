<?php
namespace App\Tests\Helpers;

use App\Entity\Utilisateurs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserHelper
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;

    public function __construct(KernelBrowser $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function createLocataire(string $email): Utilisateurs
    {
        return $this->createUser(['ROLE_LOCATAIRE'], $email);
    }

    private function createUser(array $roles, string $email): Utilisateurs
    {
        $userRepository = $this->entityManager->getRepository(Utilisateurs::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            $user = new Utilisateurs();
            $user->setEmail($email);
            $user->setPassword(password_hash('password', PASSWORD_BCRYPT));
            $user->setNom("Déesse de la putréfaction");
            $user->setPrenom("Malenia");
            $user->setStatut(0);
            $user->setRoles($roles);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return $user;
    }

    public function login(Utilisateurs $user)
    {
        $this->client->loginUser($user);
    }
}