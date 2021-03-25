<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request, UserRepository $userRepository, TranslatorInterface $translator): Response
    {
        $currentUser = $this->getUser();

        if(!$this->isGranted("IS_VERIFIED", $currentUser)){

            $message = $translator->trans('Please check your mails to verify your address');
            $this->addFlash('info', $message);
            
            return $this->redirectToRoute('app_login');

            };

        $this->denyAccessUnlessGranted("IS_VERIFIED", $currentUser, 'You have to verify your address in order to access to website.');

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $userRepository->getUserPaginator($offset);
        
        return $this->render('front/index.html.twig', [
            'user' => $currentUser,
            'users' => $paginator,
            'previous' => $offset - UserRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + UserRepository::PAGINATOR_PER_PAGE),
            ]);
    }

    /**
     * @Route("/change-locale/{locale}", name="change_locale")
     */
    public function changeLocale($locale, Request $request)
    {        

        if( in_array($locale, $this->getParameter('app.locales'))){
            $request->getSession()->set('_locale', $locale);
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
