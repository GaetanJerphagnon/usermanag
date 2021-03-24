<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\Slugger;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class UserFiller
{
    private $slugger;

    public function __construct(Slugger $slugger)
    {
        $this->slugger = $slugger;
    }

    // Trigger each time right before a user is created
    public function prePersist(User $user, LifecycleEventArgs $event)
    {
        if(!$user->getAvatar()){ $user->setAvatar('images/default_user.jpg'); };

    }
}
