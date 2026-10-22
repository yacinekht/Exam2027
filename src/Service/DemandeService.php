<?php

namespace App\Service;





use App\Entity\Animal;

use App\Entity\Registration;
use App\Entity\User;

use App\Enum\AdoptionDemandeStatus;
use Doctrine\ORM\EntityManagerInterface;


class DemandeService
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function Register(User $user, Animal $animal)
    {


        $ExistingRegistration = $this->entityManager->getRepository(Registration::class)->findOneBy([
            'user' => $user,
            'animal' => $animal
        ]);

        if ($ExistingRegistration) {
            return false;
        }

        $Registration = new Registration(AdoptionDemandeStatus::En_attente);
        $Registration->setUser($user);
        $Registration->setAnimal($animal);
        $Registration->setCreatedAt(new \DateTimeImmutable());


        $this->entityManager->persist($Registration);
        $this->entityManager->flush();


        return true;

    }
    public function UnRegister(User $user,Animal $animal){


        $registration=$this->entityManager-> getRepository(Registration::class)->findOneBy([
            'user'=>$user,
            'animal'=>$animal,
        ]);

        if(!$registration){
            return false;
        }

        $this->entityManager->remove($registration);
        $this->entityManager->flush();
        return true;
    }


}
