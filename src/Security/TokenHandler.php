<?php

namespace App\Security;

use App\Entity\Token;
use App\Repository\TokenRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class TokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private TokenRepository $tokenRepository,
    )
    {
    }

    public function getUserBadgeFrom(#[\SensitiveParameter] string $accessToken): UserBadge
    {
        $token = $this->tokenRepository->findOneBy(['token' => $accessToken]);
        if (null === $token || !$this->checkValidToken($token)) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        return new UserBadge($token->getUser()->getId());
    }

    private function checkValidToken(Token $token): bool
    {
        if (!$token->getToken()) {
            return false;
        }

        return $token->getDateExpiration()->getTimestamp() < time();
    }
}