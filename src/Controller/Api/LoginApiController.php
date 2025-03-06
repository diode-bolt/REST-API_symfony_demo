<?php

namespace App\Controller\Api;

use App\Entity\Dto\UserDto;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class LoginApiController extends AbstractController
{
    #[Route('/api/login', name: 'app_login', methods: ['POST'])]
    #[Tag(name: 'Login')]
    #[Response(
        response: 200,
        description: 'Login success',
        content: new JsonContent(ref: '#/components/schemas/login')
    )]
    #[Response(
        response: 401,
        description: 'bad credentials',
        content: new JsonContent(ref: '#/components/schemas/error')
    )]
    public function login(#[MapRequestPayload(serializationContext: ['groups'=>['login']])] UserDto $userDto): JsonResponse
    {
        return $this->json(['token'=> false]);
    }
}