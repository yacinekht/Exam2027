<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AnimalControllerTest extends WebTestCase
{
    public function testAnimalListIsReachable(): void
    {
        $client = static::createClient();
        $client->request('GET', static::getContainer()->get('router')->generate('animaux'));

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Tous les animaux à l\'adoption');
    }
}
