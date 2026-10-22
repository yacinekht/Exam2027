<?php

namespace App\Tests\Unit;

use App\Enum\AdoptionDemandeStatus;
use PHPUnit\Framework\TestCase;

final class AdoptionDemandeStatusTest extends TestCase
{
    public function testEnAttenteValue(): void
    {
        self::assertSame('En_attente', AdoptionDemandeStatus::En_attente->value);
    }

    public function testConfirmerValue(): void
    {
        self::assertSame('Confirmer', AdoptionDemandeStatus::Confirmer->value);
    }

    public function testRefuserValue(): void
    {
        self::assertSame('Refuser', AdoptionDemandeStatus::Refuser->value);
    }

    public function testThereAreThreeStatuses(): void
    {
        self::assertCount(3, AdoptionDemandeStatus::cases());
    }
}
