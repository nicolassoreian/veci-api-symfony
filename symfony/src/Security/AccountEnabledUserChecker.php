<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\AuthenticationExpiredException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountEnabledUserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->isEnabled()) {
            throw new AuthenticationExpiredException('Your account was disabled.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if ($user->isVerified()) {
            return;
        }

        // user account is expired, the user may be notified
        $now = new \DateTime();
        $interval = $user->getCreatedAt()->diff($now);
        // TODO: CREATE AN ENV VARIABLE TO THE DAYS (10)
        if ($interval->days > 7) {
            throw new AccountExpiredException('Your time to validate your email has ended.');
        }
    }
}
