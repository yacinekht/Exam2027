<?php

namespace App\Controller\DemandeAdoption;

use App\Repository\AnimalRepository;
use App\Service\DemandeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/demande/{id}', name: 'demande_adoption', methods: ['POST'])]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class DemandeAdoptionController extends AbstractController
{
    public function __invoke(DemandeService $demandeService, AnimalRepository $animalRepository, Security $security, int $id): Response {

        $user = $security->getUser();
        $animal = $animalRepository->find($id);

        if (!$animal) {
            throw $this->createNotFoundException('Animal introuvable.');
        }
        if (!$user) {
            throw $this->createAccessDeniedException("Vous n'êtes pas connecté.");
        }

        $success = $demandeService->Register($user, $animal);

        if (!$success) {
            $this->addFlash('warning', 'Tu as déjà fait une demande pour cet animal.');
        } else {
            $this->addFlash('success', 'Demande effectuée avec succès !');
        }

        return $this->redirectToRoute('animaux');
    }
}
