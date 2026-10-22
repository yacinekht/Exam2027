<?php

namespace App\Tests\Functional;

use App\Entity\Animal;
use App\Entity\Registration;
use App\Entity\User;
use App\Enum\AdoptionDemandeStatus;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AdoptionRequestTest extends WebTestCase
{
    public function testUserCanRequestAdoption(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $em = $container->get(EntityManagerInterface::class);
        $router = $container->get('router');

        // On crée un visiteur et un animal neufs : le test ne dépend pas des fixtures
        $organizer = $container->get(UserRepository::class)
            ->findOneBy(['email' => 'membre1@refuge.test']); // ADAPTER : un compte organizer

        $visitor = (new User())
            ->setEmail('visiteur'.uniqid().'@refuge.test')
            ->setFirstName('Test')
            ->setLastName('Visiteur');
        $visitor->setPassword(
            $container->get(UserPasswordHasherInterface::class)->hashPassword($visitor, 'azerty123')
        );

        $animal = (new Animal())
            ->setName('Animal test '.uniqid())
            ->setEspece('Chien')
            ->setRace('Labrador')
            ->setAge(2)
            ->setDescription('Animal créé pour le test.')
            ->setOrganizer($organizer);

        $em->persist($visitor);
        $em->persist($animal);
        $em->flush();

        $client->loginUser($visitor);
        $client->request('POST', $router->generate('demande_adoption', ['id' => $animal->getId()]));

        self::assertResponseRedirects();

        $registration = $em->getRepository(Registration::class)
            ->findOneBy(['user' => $visitor, 'animal' => $animal]);

        self::assertInstanceOf(Registration::class, $registration);
        self::assertSame(AdoptionDemandeStatus::En_attente, $registration->getStatus());
    }
}
