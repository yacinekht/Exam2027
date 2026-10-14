<?php

namespace App\Controller\Animals;

use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[Route(path:'/animaux', name:'animaux')]
class AnimalController extends AbstractController
{
    public function __invoke(Environment $twig,AnimalRepository $animalRepository):Response

    {
        return new Response( $twig->render('Animals/animal.html.twig',[
            'animal'=>$animalRepository->findAll(),
        ]),Response::HTTP_OK );
    }
}
