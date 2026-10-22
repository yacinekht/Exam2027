<?php

namespace App\Controller\Manager;

use App\Repository\RegistrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ORGANIZER')]
#[Route('/lesdemandes', name: 'demandes_list', methods: ['GET'])]
class DemandesListController extends AbstractController
{
    public function __invoke(RegistrationRepository $registrationRepository): Response
    {
        return $this->render('Manager/demandeadoption.html.twig', [
            'registrations' => $registrationRepository->findAllWithRelations(),
        ]);
    }
}
