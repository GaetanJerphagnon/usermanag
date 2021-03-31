<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class BookVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT_BOOK', 'DELETE_BOOK'])
            && $subject instanceof \App\Entity\Book;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'EDIT_BOOK':
                return $subject->getAuthor()->getId() === $user->getId();
                break;
            case 'DELETE_BOOK':
                return $subject->getAuthor()->getId() === $user->getId();
                break;
        }

        return false;
    }
}
