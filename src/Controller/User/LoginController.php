<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;
#[Route(path:'/login', name:'login')]
class LoginController extends AbstractController
{
    public function __invoke(Environment $twig, AuthenticationUtils $authenticationUtils): Response
    {
        return new Response($twig->render('User/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]),Response::HTTP_OK);
    }

}
