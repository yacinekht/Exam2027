<?php

namespace App\Repository;

use App\Entity\Registration;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Registration>
 */
class RegistrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Registration::class);
    }

    public function findYourDemandes(User $user): array
    {
        return $this->createQueryBuilder('r')
            ->addSelect('a')
            ->join('r.animal', 'a')
            ->andWhere('r.user = :user')
            ->setParameter('user', $user)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllWithRelations(): array
    {
        return $this->createQueryBuilder('r')
            ->addSelect('u', 'a')
            ->join('r.user', 'u')
            ->join('r.animal', 'a')
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
