<?php

namespace App\Tests\Unit;

use App\Entity\Animal;
use App\Entity\User;
use App\Enum\AnimalStatus;
use PHPUnit\Framework\TestCase;

final class AnimalTest extends TestCase
{
    private Animal $animal;

    protected function setUp(): void
    {
        $this->animal = new Animal();
    }

    public function testNewAnimalIsAvailableByDefault(): void
    {
        self::assertSame(AnimalStatus::Disponible, $this->animal->getStatus());
    }

    public function testNewAnimalHasArrivalDateAndNoRegistration(): void
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->animal->getArrivedAt());
        self::assertCount(0, $this->animal->getRegistrations());
    }

    public function testNameEspeceRaceAgeDescription(): void
    {
        $this->animal
            ->setName('Oscar')
            ->setEspece('Chien')
            ->setRace('Labrador')
            ->setAge(4)
            ->setDescription('Très affectueux.');

        self::assertSame('Oscar', $this->animal->getName());
        self::assertSame('Chien', $this->animal->getEspece());
        self::assertSame('Labrador', $this->animal->getRace());
        self::assertSame(4, $this->animal->getAge());
        self::assertSame('Très affectueux.', $this->animal->getDescription());
    }

    public function testRaceCanBeNull(): void
    {
        $this->animal->setRace(null);

        self::assertNull($this->animal->getRace());
    }

    public function testStatusCanBeChanged(): void
    {
        $this->animal->setStatus(AnimalStatus::Adopté);

        self::assertSame(AnimalStatus::Adopté, $this->animal->getStatus());
    }

    public function testPhotoFilenameIsNullByDefaultAndCanBeSet(): void
    {
        self::assertNull($this->animal->getPhotoFilename());

        $this->animal->setPhotoFilename('oscar.jpg');

        self::assertSame('oscar.jpg', $this->animal->getPhotoFilename());
    }

    public function testOrganizerCanBeSet(): void
    {
        $organizer = new User();
        $this->animal->setOrganizer($organizer);

        self::assertSame($organizer, $this->animal->getOrganizer());
    }
}
