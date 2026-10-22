<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Repository\RegistrationRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;

#[AsController]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Route(path: '/mes-demandes', name: 'mes_demandes', methods: ['GET'])]
class MesDemandesController
{
    public function __invoke(Environment $twig, Security $security, RegistrationRepository $registrationRepository): Response {
        $user = $security->getUser();

        if (!$user instanceof User) {
            throw new \LogicException('Utilisateur introuvable.');
        }

        return new Response($twig->render('User/mes_demandes.html.twig', [
            'demandes' => $registrationRepository->findYourDemandes($user),
        ]));
    }
}
