<?php

namespace App\Controller\DemandeAdoption;

use App\Entity\User;
use App\Repository\AnimalRepository;
use App\Service\DemandeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;
#[Route(path: '/demande', name: 'demande_adoption')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class DemandeAdoptionController extends AbstractController
{
 public function __invoke(DemandeService $demandeService, $animalRepository,int $id, $twig , User $user , Security $security): Response{

     $user=$security->getUser();
     $animal=$animalRepository->find($id);

     if(!$animal){
         throw $this->createNotFoundException('Animal introuvable.');

     }
     if (!$user) {
         throw $this->createAccessDeniedException("Vous n'êtes pas connecté.");
     }

     $success=$demandeService->register($user, $animal);

     if(!$success){
         $this->addFlash('warning', 'Tu es déjà fait une demande pour cet animal.');

     }else{
         $this->addFlash('success', 'Retractation effectuée avec succès !');

     }
     return new Response($twig->render('DemandeAdoption/demande_adoption.html.twig',[
         'Demande'=>$user->getRegistrations(),
     ]));


 }



}
