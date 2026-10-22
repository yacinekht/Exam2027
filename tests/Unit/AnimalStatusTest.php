<?php

namespace App\Tests\Unit;

use App\Enum\AnimalStatus;
use PHPUnit\Framework\TestCase;

final class AnimalStatusTest extends TestCase
{
    public function testDisponibleValue(): void
    {
        self::assertSame('Disponible_a_l\'adoption', AnimalStatus::Disponible->value);
    }

    public function testEnCoursValue(): void
    {
        self::assertSame('En_Cours_d\'adoption', AnimalStatus::En_Cours->value);
    }

    public function testAdopteValue(): void
    {
        self::assertSame('Adopté', AnimalStatus::Adopté->value);
    }

    public function testUnknownValueReturnsNull(): void
    {
        self::assertNull(AnimalStatus::tryFrom('inconnu'));
    }
}
