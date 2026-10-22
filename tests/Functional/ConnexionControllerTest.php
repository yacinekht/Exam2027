<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ConnexionControllerTest extends WebTestCase
{
    public function testConnexion(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertAnySelectorTextContains('a', 'Connexion');

        $link = $crawler->selectLink('Connexion / S\'inscrire')->link();
        $client->click($link);

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Connexion');
    }

    public function testInscriptionPageIsReachable(): void
    {
        $client = static::createClient();
        $client->request('GET', static::getContainer()->get('router')->generate('inscription'));

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Créer un compte');
    }
}
