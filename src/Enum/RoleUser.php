<?php

namespace App\Enum;

enum RoleUser :string
{
    case Admin ='ROLE_ADMIN';
    case User ='ROLE_USER'; // les visiteurs
    case organizer ='ROLE_ORGANIZER';  // les membre de l'asso
}
