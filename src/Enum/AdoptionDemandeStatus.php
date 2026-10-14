<?php

namespace App\Enum;

enum AdoptionDemandeStatus : string
{
 case En_attente ='En_attente';
 case Confirmer ='Confirmer';

 case Refuser ='Refuser';

 //// donc soit quand tu vas dmd a adopter un aniaml quand tu iras sur mes demandes d'adoption tu trouveras cet animal avec ton status soit t accepter et c ton animal soit t encore en attente on a pas decider soit c mort pour toi
}
