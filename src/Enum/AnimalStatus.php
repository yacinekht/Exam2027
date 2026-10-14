<?php

namespace App\Enum;

enum AnimalStatus : string
{
    case Disponible ='Disponible_a_l\'adoption';
    case En_Cours ='En_Cours_d\'adoption';

    case Adopté='Adopté';
}
