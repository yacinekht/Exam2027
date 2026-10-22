<?php

namespace App\Tests\Unit;

use App\Entity\User;
use App\Enum\RoleUser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User();
    }

    public function testImplementsExpectedInterfaces(): void
    {
        self::assertInstanceOf(UserInterface::class, $this->user);
        self::assertInstanceOf(PasswordAuthenticatedUserInterface::class, $this->user);
    }

    public function testEmailAndUserIdentifier(): void
    {
        $this->user->setEmail('user1@refuge.test');

        self::assertSame('user1@refuge.test', $this->user->getEmail());
        self::assertSame('user1@refuge.test', $this->user->getUserIdentifier());
    }

    public function testPasswordGetterAndSetter(): void
    {
        $this->user->setPassword('azerty');

        self::assertSame('azerty', $this->user->getPassword());
    }

    public function testFirstNameLastNameAndFullName(): void
    {
        $this->user->setFirstName('Julien')->setLastName('Bernard');

        self::assertSame('Julien', $this->user->getFirstName());
        self::assertSame('Bernard', $this->user->getLastName());
        self::assertSame('Julien Bernard', $this->user->getFullName());
    }

    public function testDefaultRoleIsUser(): void
    {
        self::assertSame([RoleUser::User->value], $this->user->getRoles());
    }

    public function testRolesCanBeSet(): void
    {
        $this->user->setRoles([RoleUser::Admin->value]);

        self::assertContains('ROLE_ADMIN', $this->user->getRoles());
    }

    public function testRolesAreUnique(): void
    {
        $this->user->setRoles(['ROLE_USER', 'ROLE_USER']);

        self::assertCount(1, $this->user->getRoles());
    }

    public function testNewUserHasCreatedAtAndEmptyCollections(): void
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->user->getCreatedAt());
        self::assertCount(0, $this->user->getAnimals());
        self::assertCount(0, $this->user->getRegistrations());
    }
}
