<?php

declare(strict_types=1);

namespace Skrepr\SkreprAuthBundle\Security\User;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function refreshUser(UserInterface $user): KeyCloakUser
    {
        if (!$user instanceof KeyCloakUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_debug_type($user)));
        }

        return new KeyCloakUser($user->getId(), $user->getEmail(), $user->getRoles());
    }

    public function supportsClass(string $class): bool
    {
        return KeyCloakUser::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return new KeyCloakUser($identifier);
    }
}
