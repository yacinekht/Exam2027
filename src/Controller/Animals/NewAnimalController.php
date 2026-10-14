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
#[Route('/animaux/new', name: 'new_animal')]
class NewAnimalController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $em, PhotoUploader $uploader): Response
    {
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photoFilename')->getData();
            if ($photo) {
                $animal->setPhotoFilename($uploader->upload($photo));
            }

            $animal->setOrganizer($this->getUser());
            $em->persist($animal);
            $em->flush();

            $this->addFlash('success', 'Animal ajouté.');

            return $this->redirectToRoute('animaux');
        }

        return $this->render('Animals/newAnimal.html.twig', [
            'form' => $form,
        ]);
    }
}
