<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {

        $user = $this->getUser();
        
        return $this->render('front/index.html.twig', [
            'user' => $user,
            'controller_name' => 'FrontController',
        ]);
    }
}
