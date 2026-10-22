<?php
// src/Controller/Event/ChangeRegistrationStatusController.php
namespace App\Controller\Manager;

use App\Entity\Registration;
use App\Enum\AdoptionDemandeStatus;
use App\Service\RegistrationStatusManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
#[IsGranted('ROLE_ORGANIZER')]
#[Route(path:'/lesdemandes/{id}/statut', name: 'registration_change_status', requirements: ['id' => '\d+'], methods: ['POST'])]
class NosDemandeController extends AbstractController
{
    public function __invoke(Registration $registration, Request $request, RegistrationStatusManager $manager): Response
    {
//        if (!$this->isCsrfTokenValid('registration_status_' . $registration->getId(), (string) $request->request->get('_token'))) {
//            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
//        }

        $newStatus = AdoptionDemandeStatus::tryFrom((string) $request->request->get('status'));
        if (null === $newStatus) {
            $this->addFlash('danger', 'Statut invalide.');

            return $this->redirectToRoute('demandes_list');
        }

        try {
            $manager->changeStatus($registration, $newStatus);
            $this->addFlash('success', 'La demande a été mise à jour.');
        } catch (\DomainException $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToRoute('demandes_list');
    }
}


