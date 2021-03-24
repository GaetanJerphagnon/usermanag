<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class Slugger
{
    private $em;
    private $slugger;

    public function __construct(EntityManagerInterface $em, SluggerInterface $slugger)
    {
        $this->em = $em;
        $this->slugger = $slugger;
    }

    /**
     * @param string $string The string to slugify
     * @return string The new slug
     */
    public function slugify($string)
    {
        return strtolower($this->slugger->slug($string));
    }

    /**
     * @param User $user
     * @return User
     */
    public function slugifyUser(User $user)
    {
        $slugWithId =
            $this->slugify($user->getFirstname()) .
            '-' .
            $this->slugify($user->getLastname()) .
            '-' .
            $user->getId();
            
        $user->setSlug($slugWithId);

        $this->em->flush();
        return $user;
    }
}
