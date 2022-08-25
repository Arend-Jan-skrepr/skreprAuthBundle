<?php

declare(strict_types=1);

namespace Skrepr\SkreprAuthBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;

class KeyCloakUser implements UserInterface
{
    private string $email;

    private array $roles;

    private ?string $id;

    public function __construct(?string $id = null, string $email = '', array $roles = [])
    {
        $this->email = $email;
        $this->roles = $roles;
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): KeyCloakUser
    {
        $this->email = $email;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): KeyCloakUser
    {
        $this->roles = $roles;
        return $this;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}