<?php

namespace App\Security\Voter;

use App\Entity\Review;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ReviewVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT_REVIEW','DELETE_REVIEW'])
            && $subject instanceof \App\Entity\Review;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface || !$subject instanceof Review) {
            return false;
        }

        switch ($attribute) {
            case 'EDIT_REVIEW':
                return $subject->getAuthor()->getId() === $user->getId();
                break;
            case 'DELETE_REVIEW':
                return $subject->getAuthor()->getId() === $user->getId();
                break;
        }

        return false;
    }
}
