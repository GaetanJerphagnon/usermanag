<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request, UserRepository $userRepository, MailerInterface $mailer): Response
    {
        $currentUser = $this->getUser();

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $userRepository->getUserPaginator($offset);
        
        return $this->render('front/index.html.twig', [
            'user' => $currentUser,
            'users' => $paginator,
            'previous' => $offset - UserRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + UserRepository::PAGINATOR_PER_PAGE),
            ]);
    }
}
