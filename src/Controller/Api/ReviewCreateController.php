<?php

namespace App\Controller\Api;

use App\Entity\Review;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class ReviewCreateController extends AbstractController
{
    private $security;

    public function __construct( Security $security)
    {
        $this->security = $security;
    }

    public function __invoke(Review $data, Request $request)
    {
        $data->setAuthor($this->security->getUser());

        return $data;
    }
}
