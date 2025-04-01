<?php

namespace App\Enum;

enum OffreStatus: int
{
    case Inactif = 0;
    case Actif = 1;
    case Terminee = 2;
    case Suspendue = 3;
}