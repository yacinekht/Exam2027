<?php

namespace App\Tests\Functional;

use App\Entity\Animal;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class FunctionalCreateAnimalTest extends WebTestCase
{
    public function testOrganizerCanCreateAnimal(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $organizer = $container->get(UserRepository::class)
            ->findOneBy(['email' => 'membre1@refuge.test']); // ADAPTER : un compte ADMIN ou ORGANIZER
        $client->loginUser($organizer);

        $name = 'Test animal '.uniqid();

        $crawler = $client->request('GET', $container->get('router')->generate('new_animal'));
        self::assertResponseIsSuccessful();

        // ADAPTER : le préfixe et les noms des champs dépendent de votre AnimalType
        $form = $crawler->selectButton('Enregistrer l\'animal')->form([
            'animal[name]' => $name,
            'animal[espece]' => 'Chien',
            'animal[race]' => 'Berger Allemand',
            'animal[age]' => 3,
            'animal[description]' => 'Description de test fonctionnel.',
        ]);

        $client->submit($form);

        self::assertResponseRedirects();

        $animal = $container->get(EntityManagerInterface::class)
            ->getRepository(Animal::class)
            ->findOneBy(['name' => $name]);

        self::assertInstanceOf(Animal::class, $animal);
        self::assertSame($organizer->getId(), $animal->getOrganizer()->getId());
    }
}
