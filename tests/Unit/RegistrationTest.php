<?php

namespace App\Tests\Unit;

use App\Entity\Animal;
use App\Entity\Registration;
use App\Entity\User;
use App\Enum\AdoptionDemandeStatus;
use PHPUnit\Framework\TestCase;

final class RegistrationTest extends TestCase
{
    public function testNewRegistrationIsPendingByDefault(): void
    {
        $registration = new Registration();

        self::assertSame(AdoptionDemandeStatus::En_attente, $registration->getStatus());
        self::assertInstanceOf(\DateTimeImmutable::class, $registration->getCreatedAt());
    }

    public function testStatusCanBeGivenToConstructor(): void
    {
        $registration = new Registration(AdoptionDemandeStatus::Confirmer);

        self::assertSame(AdoptionDemandeStatus::Confirmer, $registration->getStatus());
    }

    public function testStatusCanBeChanged(): void
    {
        $registration = new Registration();
        $registration->setStatus(AdoptionDemandeStatus::Refuser);

        self::assertSame(AdoptionDemandeStatus::Refuser, $registration->getStatus());
    }

    public function testUserAndAnimalCanBeLinked(): void
    {
        $user = new User();
        $animal = new Animal();

        $registration = (new Registration())->setUser($user)->setAnimal($animal);

        self::assertSame($user, $registration->getUser());
        self::assertSame($animal, $registration->getAnimal());
    }
}
