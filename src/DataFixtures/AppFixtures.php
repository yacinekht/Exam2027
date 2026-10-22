<?php

namespace App\DataFixtures;

use App\Entity\Animal;
use App\Entity\Registration;
use App\Entity\User;
use App\Enum\AdoptionDemandeStatus;
use App\Enum\AnimalStatus;
use App\Enum\RoleUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private const PASSWORD = 'password';

    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // ---------- Utilisateurs ----------
        $this->createUser($manager, 'admin@refuge.test', 'Alice', 'Admin', [RoleUser::Admin->value]);

        $organizers = [
            $this->createUser($manager, 'membre1@refuge.test', 'Marc', 'Dupont', [RoleUser::organizer->value]),
            $this->createUser($manager, 'membre2@refuge.test', 'Sophie', 'Martin', [RoleUser::organizer->value]),
        ];

        // ROLE_USER est ajouté automatiquement par User::getRoles()
        $adopters = [
            $this->createUser($manager, 'user1@refuge.test', 'Julien', 'Bernard'),
            $this->createUser($manager, 'user2@refuge.test', 'Camille', 'Petit'),
            $this->createUser($manager, 'user3@refuge.test', 'Lucas', 'Moreau'),
            $this->createUser($manager, 'user4@refuge.test', 'Emma', 'Leroy'),
        ];

        // ---------- Animaux ----------
        // [nom, espèce, race, âge, description, statut]
        $animalsData = [
            ['Rex', 'Chien', 'Berger allemand', 4, 'Chien loyal et énergique, adore les longues promenades. Bien avec les enfants.', AnimalStatus::Disponible],
            ['Luna', 'Chat', 'Européen', 2, 'Chatte douce et câline, habituée à la vie en appartement.', AnimalStatus::Disponible],
            ['Milo', 'Chien', 'Labrador', 1, 'Jeune chien joueur et sociable, en cours d\'éducation.', AnimalStatus::Disponible],
            ['Chipie', 'Chat', 'Siamois', 6, 'Chatte pleine de caractère, cherche un foyer calme.', AnimalStatus::Disponible],
            ['Nala', 'Chien', 'Border collie', 3, 'Très intelligente, a besoin de beaucoup d\'activité et de stimulation.', AnimalStatus::Disponible],
            ['Tigrou', 'Chat', 'Maine coon', 5, 'Grand chat paisible et affectueux, s\'entend bien avec les autres chats.', AnimalStatus::Disponible],
            ['Biscotte', 'Lapin', 'Nain', 1, 'Lapin curieux et sociable, habitué à être manipulé.', AnimalStatus::Disponible],
            ['Oscar', 'Chien', 'Beagle', 7, 'Chien calme et gourmand, idéal pour une famille tranquille.', AnimalStatus::En_Cours],
            ['Mimi', 'Chat', 'Persan', 8, 'Chatte âgée très affectueuse, besoin de brossages réguliers.', AnimalStatus::En_Cours],
            ['Foxy', 'Chien', 'Husky', 2, 'Chien athlétique qui demande un grand espace et un maître expérimenté.', AnimalStatus::En_Cours],
            ['Pistache', 'Chat', 'Européen', 1, 'Chaton vif et joueur, adopté récemment.', AnimalStatus::Adopté],
            ['Balto', 'Chien', 'Croisé', 9, 'Vieux chien sage et doux, a trouvé sa famille.', AnimalStatus::Adopté],
            ['Caramel', 'Lapin', 'Bélier', 2, 'Lapin tranquille et gourmand, adopté par une famille.', AnimalStatus::Adopté],
            ['Jade', 'Chien', 'Cavalier King Charles', 4, 'Chienne très douce, parfaite compagne pour une personne seule.', AnimalStatus::Disponible],
            ['Simba', 'Chat', 'Bengal', 3, 'Chat actif et joueur, a besoin d\'espace pour grimper et explorer.', AnimalStatus::Disponible],
        ];

        $animals = [];
        foreach ($animalsData as $i => [$name, $espece, $race, $age, $description, $status]) {
            $animal = (new Animal())
                ->setName($name)
                ->setEspece($espece)
                ->setRace($race)
                ->setAge($age)
                ->setDescription($description)
                ->setStatus($status)
                ->setOrganizer($organizers[$i % 2])
                ->setArrivedAt(new \DateTimeImmutable(sprintf('-%d days', 5 + $i * 12)));

            $manager->persist($animal);
            $animals[] = $animal;
        }

        // ---------- Demandes d'adoption ----------
        // [index adoptant, index animal, statut] (couples uniques)
        $registrationsData = [
            [0, 7, AdoptionDemandeStatus::En_attente],  // Oscar (en cours)
            [1, 7, AdoptionDemandeStatus::En_attente],
            [2, 8, AdoptionDemandeStatus::En_attente],  // Mimi (en cours)
            [3, 9, AdoptionDemandeStatus::En_attente],  // Foxy (en cours)
            [0, 9, AdoptionDemandeStatus::Refuser],
            [1, 10, AdoptionDemandeStatus::Confirmer],  // Pistache (adopté)
            [2, 10, AdoptionDemandeStatus::Refuser],
            [3, 11, AdoptionDemandeStatus::Confirmer],  // Balto (adopté)
            [0, 12, AdoptionDemandeStatus::Confirmer],  // Caramel (adopté)
            [1, 0, AdoptionDemandeStatus::En_attente],  // Rex
            [2, 0, AdoptionDemandeStatus::En_attente],
            [0, 1, AdoptionDemandeStatus::En_attente],  // Luna
            [3, 2, AdoptionDemandeStatus::Refuser],     // Milo
        ];

        foreach ($registrationsData as $i => [$userIndex, $animalIndex, $status]) {
            $registration = (new Registration($status))
                ->setUser($adopters[$userIndex])
                ->setAnimal($animals[$animalIndex])
                ->setCreatedAt(new \DateTimeImmutable(sprintf('-%d days', 1 + $i * 2)));

            $manager->persist($registration);
        }

        $manager->flush();
    }
    private function createUser(
        ObjectManager $manager,
        string $email,
        string $firstName,
        string $lastName,
        array $roles = [],
    ): User {
        $user = (new User())
            ->setEmail($email)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setRoles($roles);

        $user->setPassword($this->hasher->hashPassword($user, self::PASSWORD));
        $manager->persist($user);

        return $user;
    }
}
