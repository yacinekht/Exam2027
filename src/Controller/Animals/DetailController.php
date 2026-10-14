<?php

namespace App\Controller\Animals;

use App\Repository\AnimalRepository;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;
#[Route(path:'/animaux/{id}', name:'animal_detail', requirements: ['id' => '\d+'])]
class DetailController extends AbstractController
{
    public function __invoke(Environment $twig, AnimalRepository $animalRepository, int $id): Response{
        return new Response($twig->render('Animals/details_animal.html.twig',[
            'animal'=>$animalRepository->find($id)

        ]));
    }
}
