<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginSubscriber implements EventSubscriberInterface
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        
        if ($user instanceof \App\Entity\User){
            $user->setLastConnectedAt(new \DateTime());
            $this->manager->persist($user);
            $this->manager->flush();
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'security.interactive_login' => 'onSecurityInteractiveLogin',
        ];
    }
}
