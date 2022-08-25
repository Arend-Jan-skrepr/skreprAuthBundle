<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Stevenmaguire\OAuth2\Client\Provider\KeycloakResourceOwner;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class AuthenticatorKeyCloakUser extends OAuth2Authenticator
{
    const REDIRECT_ROUTE = 'index';
    const CHECK_ROUTE = 'check';

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private ClientRegistry $clientRegistry,
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return self::CHECK_ROUTE === $request->attributes->get('_route');
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->getKeyCloakClient();
        $accessToken = $this->fetchAccessToken($client);

        try {
            /** @var KeycloakResourceOwner $userToken */
            $userToken = $client->fetchUserFromToken($accessToken);
        } catch (\Exception $e) {
            throw new AuthenticationException('Something went wrong with keycloak');
        }

        return new SelfValidatingPassport(
            new UserBadge($userToken->getEmail(), function () use ($userToken) {
                $user = $this->userRepository->findOneBy(['email' => $userToken->getEmail()]);

                if (null === $user) {
                    $user = $this->updateUser(new User(), $userToken);
                }

                return new KeyCloakUser($user->getId(), $userToken->getEmail(), $userToken->toArray()['roles']);
            })
        );
    }

    public function updateUser(User $user, KeycloakResourceOwner $keyCloak): User
    {
        if (!array_key_exists('preferred_username', $keyCloak->toArray())) {
            throw new AuthenticationException('name is unknown');
        }

        if (!array_key_exists('email', $keyCloak->toArray())) {
            throw new AuthenticationException('email is unknown missing');
        }

        if (!array_key_exists('roles', $keyCloak->toArray())) {
            throw new AuthenticationException('User roles are unknown');
        }

        $user
            ->setName($keyCloak->toArray()['preferred_username'])
            ->setEmail($keyCloak->toArray()['email'])
            ->setRoles($keyCloak->toArray()['roles'])
            ->setPassword('plain')
            ->setFunction('Skrepr')
        ;

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate(self::REDIRECT_ROUTE));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    private function getKeyCloakClient(): OAuth2ClientInterface
    {
        return $this->clientRegistry->getClient('keycloak');
    }
}