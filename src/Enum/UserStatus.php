<?php

namespace App\Enum;

enum UserStatus: int
{
    case Inactif = 0;
    case Actif = 1;
    case Exclu = 2;
}