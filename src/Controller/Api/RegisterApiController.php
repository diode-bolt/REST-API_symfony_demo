<?php

namespace App\Controller\Api;

use App\Entity\Dto\UserDto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use OpenApi\Attributes\JsonContent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;


class RegisterApiController extends AbstractController
{
    #[Route('/api/register', name: 'app_user_api_new', methods: ['POST'])]
    #[OA\Tag('Register')]
    #[OA\Response(
        response: 401,
        description: 'bad JWT token',
        content: new JsonContent(ref: '#/components/schemas/error')
    )]
    #[OA\Response(
        response: 200,
        description: 'success',
        content: new JsonContent(ref: '#/components/schemas/login')
    )]
    #[OA\Response(
        response: 422,
        description: 'validation fail',
        content: new JsonContent(ref: '#/components/schemas/validationFailed')
    )]
    public function register(
        #[MapRequestPayload(serializationContext: ['groups'=>['update']])] UserDto $userDto,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $hasher,
        JWTTokenManagerInterface $jwtManager
    ): Response
    {
        $user = User::createFromDto($userDto);
        $user->setPassword($hasher->hashPassword($user, $userDto->password));
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(['success' => true, 'token' => $jwtManager->create($user)]);
    }
}