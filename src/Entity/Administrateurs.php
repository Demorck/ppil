<?php

namespace App\Entity;

use App\Repository\AdministrateursRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdministrateursRepository::class)]
class Administrateurs extends Utilisateurs
{

}
