# 🐾 AdopteUnCompagnon

**AdopteUnCompagnon** est un site web d'adoption d'animaux développé avec **Symfony 8**. Il permet à une association de présenter les animaux qui cherchent un foyer, de recevoir des demandes d'adoption et de les traiter.

Le site est volontairement **coloré et chaleureux** pour donner envie à tout le monde, y compris aux enfants, d'adopter un compagnon. Chaque animal est présenté avec **sa photo**.

## Auteur

Projet réalisé **en solo** par **Yacine Khiat**, étudiant en 2ᵉ année à **Enigma School**.

---

## À quoi sert le site ?

Le site met en relation des personnes qui souhaitent adopter un animal et les membres de l'association qui s'en occupent. Les animaux sont présentés avec leur nom, leur espèce, leur race, leur âge, leur description, leur statut et leur photo. Les adoptants déposent leur demande en ligne, et l'association la traite depuis une interface dédiée.

---

## Ce que chacun peut faire

### Visiteur (sans compte)
- Consulter la page d'accueil
- Parcourir la liste des animaux et ouvrir la fiche détaillée de chacun
- Créer un compte (prénom, nom, email, mot de passe avec confirmation)
- Se connecter

### Utilisateur connecté (`ROLE_USER`)
- Tout ce que peut faire un visiteur
- Faire une **demande d'adoption** pour un animal (une seule demande par animal)
- **Se rétracter** : retirer sa demande d'adoption
- Consulter **« Mes demandes »** : la liste de ses demandes avec leur statut, de la plus récente à la plus ancienne
- Se déconnecter

### Membre de l'association / modérateur (`ROLE_ORGANIZER`)
- Tout ce que peut faire un utilisateur connecté
- **Ajouter un animal** (avec sa photo)
- **Modifier un animal** (informations, statut, photo)
- **Supprimer un animal** depuis la page « Nos animaux »
- **Gérer les demandes** : voir toutes les demandes d'adoption (date, demandeur, animal, statut de l'animal, statut de la demande) et **accepter**, **refuser** ou remettre **en attente** une demande

### Administrateur (`ROLE_ADMIN`)
- Dispose de **tous les droits** des membres de l'association et des utilisateurs (hiérarchie de rôles : admin → organisateur → utilisateur)

---

## Fonctionnement des demandes d'adoption

- Une demande est créée au statut **En attente**.
- L'utilisateur peut **se rétracter** tant qu'il le souhaite : sa demande est alors supprimée.
- Lorsqu'un modérateur **accepte** une demande :
  - l'animal passe au statut **Adopté** ;
  - **toutes les autres demandes** en attente pour cet animal sont **automatiquement refusées**.
- Si une demande acceptée est modifiée (refusée ou remise en attente), l'animal redevient **disponible**. Si toutes les demandes d'un animal sont refusées, il redevient aussi disponible.
- Un animal ne peut être attribué qu'à **une seule personne** à la fois.

### Historique des adoptions
Il n'existe volontairement **pas de bouton de suppression dans la page « Demandes d'adoption »**. Le choix a été fait de conserver un petit **historique** des demandes acceptées et refusées. Si l'association souhaite vraiment supprimer un animal (adopté ou non), elle peut le faire depuis la page **« Nos animaux »**.

---

## Statuts

| Élément | Statuts possibles |
|---------|-------------------|
| Animal | Disponible à l'adoption · En cours d'adoption · Adopté |
| Demande d'adoption | En attente · Acceptée (`Confirmer`) · Refusée (`Refuser`) |
| Rôles | `ROLE_USER` · `ROLE_ORGANIZER` · `ROLE_ADMIN` |

---

## Technologies

- **Symfony 8** (PHP)
- **Doctrine ORM** : entités, repositories
- **Twig** : templates
- **Symfony Form / Validator** : formulaires et validation
- **Symfony Security** : connexion, rôles, contrôle des accès
- **Docker** : le site a été dockerisé
- **PHPUnit** : tests
- **DoctrineFixtures** : jeu de données de test

---

## Organisation du code

Chaque action est un controller invocable, rangé par domaine :

```
src/
├── Controller/
│   ├── HomeController.php
│   ├── Animals/            # Liste, détail, ajout, modification, suppression
│   ├── User/               # Inscription, connexion, déconnexion, mes demandes
│   ├── DemandeAdoption/    # Faire une demande, se rétracter
│   └── Manager/            # Liste des demandes, changement de statut
├── Entity/                 # User, Animal, Registration (demande d'adoption)
├── Enum/                   # AnimalStatus, AdoptionDemandeStatus, RoleUser
├── Form/                   # Formulaires (animaux, inscription, connexion)
├── Repository/
├── Service/                # DemandeService, RegistrationStatusManager, PhotoUploader
└── DataFixtures/           # AppFixtures
templates/                  # Vues Twig
tests/                      # Tests unitaires et fonctionnels
```

L'entité `Registration` correspond à une **demande d'adoption**.

### Modèle de données
- **User** : email (unique), mot de passe haché, prénom, nom, rôles, date de création
- **Animal** : nom, espèce, race, âge, description, statut, photo, date d'arrivée, membre qui l'a ajouté
- **Registration** : utilisateur, animal, statut, date. Une contrainte d'unicité empêche un même utilisateur de demander deux fois le même animal.

### Photos
Les photos des animaux sont envoyées à l'ajout ou à la modification d'un animal (formats JPG, PNG ou WEBP, 3 Mo maximum). Lors d'une modification avec une nouvelle photo, l'ancienne est supprimée.

---

## Données de test (DataFixtures)

Des **fixtures** permettent de remplir rapidement la base avec des données de test : des utilisateurs, 15 animaux (chiens, chats, lapins) dans différents statuts, et des demandes d'adoption variées.

```bash
php bin/console doctrine:fixtures:load
```

Comptes de test (mot de passe : `password`) :

| Rôle | Email |
|------|-------|
| Administrateur | `admin@refuge.test` |
| Membres de l'association | `membre1@refuge.test`, `membre2@refuge.test` |
| Utilisateurs | `user1@refuge.test` à `user4@refuge.test` |

---

## Tests

Des tests ont été ajoutés pour vérifier les différentes fonctionnalités du projet.

**Tests unitaires** : entités `User`, `Animal`, `Registration` et enums (`AnimalStatus`, `AdoptionDemandeStatus`, `RoleUser`).

**Tests fonctionnels** :
- accessibilité de la page d'accueil, de la liste des animaux, de la connexion et de l'inscription ;
- contrôle des accès (page « Mes demandes » pour un anonyme, gestion des demandes selon le rôle) ;
- dépôt d'une demande d'adoption par un utilisateur ;
- création d'un animal par un membre de l'association.

```bash
php bin/phpunit
```

---

## Aperçu

Navigation principale : **Accueil · Nos Animaux · Mes demandes · Ajouter un nouvel animal · Gérer les demandes** (les deux derniers liens sont visibles pour les membres de l'association et les administrateurs).

- **Accueil** : message d'accueil, bouton « Voir les animaux à l'adoption » et présentation de l'esprit du site
- **Nos Animaux** : cartes colorées avec photo, statut, race, âge, description courte et boutons « Voir les détails » et « Adopter »
- **Demandes d'adoption** : tableau de toutes les demandes avec les actions Accepter / Refuser / En attente
