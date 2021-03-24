<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\Slugger;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class UserSlugger
{
    private $slugger;

    public function __construct(Slugger $slugger)
    {
        $this->slugger = $slugger;
    }

    // Trigger each time right after a user is created
    public function postPersist(User $user, LifecycleEventArgs $event)
    {

        if(!$user->getSlug()){ $this->slugger->slugifyUser($user); };

    }
}
