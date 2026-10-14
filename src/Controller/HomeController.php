<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[Route(path:'/', name:'home')]
class HomeController extends AbstractController
{

    public function __invoke(Environment $twig):Response{

        return new Response( $twig->render('Home/home.html.twig'));
    }

}
