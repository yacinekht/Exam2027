<?php

namespace App\Controller\Animals;

use App\Entity\Animal;
use App\Form\Animals\AnimalType;
use App\Service\PhotoUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_ORGANIZER")'))]
#[Route('/animaux/edit/{id}', name: 'edit_animal')]
class EditAnimalController extends AbstractController
{
    public function __invoke(Animal $animal, Request $request, EntityManagerInterface $em, PhotoUploader $uploader): Response
    {
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photoFilename')->getData();
            if ($photo) {
                $uploader->remove($animal->getPhotoFilename()); // supprime l'ancienne photo
                $animal->setPhotoFilename($uploader->upload($photo));
            }

            $em->flush();

            $this->addFlash('success', 'Animal modifié.');

            return $this->redirectToRoute('animaux');
        }

        return $this->render('Animals/editAnimal.html.twig', [
            'form' => $form,
            'animal' => $animal,
        ]);
    }
}
