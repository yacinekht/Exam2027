<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AccessControlTest extends WebTestCase
{
    public function testMesDemandesRedirectsAnonymousToLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', static::getContainer()->get('router')->generate('mes_demandes'));

        // ADAPTER : suppose que cette page est protégée dans security.yaml
        self::assertResponseRedirects();
    }

    public function testManageRequestsIsForbiddenForSimpleUser(): void
    {
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)
            ->findOneBy(['email' => 'user1@refuge.test']);
        $client->loginUser($user);

        $client->request('GET', static::getContainer()->get('router')->generate('demandes_list'));

        // ADAPTER : 403 si la page est réservée aux organisateurs/admins
        self::assertResponseStatusCodeSame(403);
    }

    public function testManageRequestsIsAllowedForOrganizer(): void
    {
        $client = static::createClient();
        $organizer = static::getContainer()->get(UserRepository::class)
            ->findOneBy(['email' => 'membre1@refuge.test']);
        $client->loginUser($organizer);

        $client->request('GET', static::getContainer()->get('router')->generate('demandes_list'));

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Demandes d\'adoption');
    }
}
