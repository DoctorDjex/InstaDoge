<?php

namespace Contest\ContestBundle\Security;


use Contest\ContestBundle\Entity\Contest;
use Contest\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ContestVoter extends Voter{
    const VIEW = 'view';
    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject){
        if (!in_array($attribute, array(self::VIEW))) {
            return false;
        }
        if (!$subject instanceof Contest) {
            return false;
        }
        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token){
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        // you know $subject is a Post object, thanks to supports
        /** @var Contest $contest */
        $contest = $subject;

        switch($attribute) {
            case self::VIEW:
                return ($user === $contest->getOwner());
        }
    }
}