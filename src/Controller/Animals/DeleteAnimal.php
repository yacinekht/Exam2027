<?php

namespace App\Controller\Animals;

use App\Entity\Animal;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/animaux/{id}/delete', name:'animaux_delete', methods: ['POST'])]
class DeleteAnimal extends AbstractController
{
    public function __invoke(EntityManagerInterface $entityManager,Animal $animal): Response{

        $entityManager->remove($animal);
        $entityManager->flush();

        return $this->redirectToRoute('animaux');
    }
}
