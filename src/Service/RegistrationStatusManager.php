<?php

namespace App\Service;

use App\Entity\Registration;
use App\Enum\AdoptionDemandeStatus;
use App\Enum\AnimalStatus;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationStatusManager
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function changeStatus(Registration $registration, AdoptionDemandeStatus $new): void
    {
        $old = $registration->getStatus();
        if ($old === $new) {
            return;
        }

        $animal = $registration->getAnimal();

        if ($new === AdoptionDemandeStatus::Confirmer) {
            foreach ($animal->getRegistrations() as $other) {
                if ($other !== $registration && $other->getStatus() === AdoptionDemandeStatus::Confirmer) {
                    throw new \DomainException(
                        'Cet animal est déjà attribué à une autre personne. Modifiez d\'abord cette demande.'
                    );
                }
            }

            $registration->setStatus($new);
            $animal->setStatus(AnimalStatus::Adopté);

            // Les autres demandes en attente sont automatiquement refusées
            foreach ($animal->getRegistrations() as $other) {
                if ($other !== $registration && $other->getStatus() === AdoptionDemandeStatus::En_attente) {
                    $other->setStatus(AdoptionDemandeStatus::Refuser);
                }
            }
        } else {
            $registration->setStatus($new);

            // Une demande qui était acceptée ne l'est plus : l'animal redevient disponible
            if ($old === AdoptionDemandeStatus::Confirmer) {
                $animal->setStatus(AnimalStatus::Disponible);
            }


        }

        $allRefused = true;

        foreach ($animal->getRegistrations() as $demande) {
            if ($demande->getStatus() !== AdoptionDemandeStatus::Refuser) {
                $allRefused = false;
                break;
            }
        }

        if ($allRefused) {
            $animal->setStatus(AnimalStatus::Disponible);
        }

        $this->em->flush(); // un seul flush : tout est enregistré ensemble ou rien
    }
}
