<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\User\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
#[Route(path:'/inscription', name:'inscription')]
class InscriptionController extends AbstractController
{

    public function __invoke(Environment $twig,RouterInterface $router,FormFactoryInterface $formFactory,Request $request,EntityManagerInterface $entityManager): Response{
        $user=new User();
        $form=$formFactory->create(InscriptionType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $user->setPassword(password_hash($user->getPassword(),PASSWORD_ARGON2ID));

            $entityManager->persist($user);
            $entityManager->flush();

            return new RedirectResponse($router->generate('animaux'));

        }
        return new Response( $twig->render('User/inscription.html.twig',[
            'user'=> $user,
            'form'=>$form->createView(),
        ]));
    }
}
