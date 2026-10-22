<?php

namespace App\Tests\Unit;

use App\Enum\RoleUser;
use PHPUnit\Framework\TestCase;

final class RoleUserTest extends TestCase
{
    public function testAdminValue(): void
    {
        self::assertSame('ROLE_ADMIN', RoleUser::Admin->value);
    }

    public function testOrganizerValue(): void
    {
        self::assertSame('ROLE_ORGANIZER', RoleUser::organizer->value);
    }

    public function testUserValue(): void
    {
        self::assertSame('ROLE_USER', RoleUser::User->value);
    }
}
