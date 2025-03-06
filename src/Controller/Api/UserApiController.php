<?php

namespace App\Controller\Api;

use App\Entity\Dto\UserDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/users')]
final class UserApiController extends AbstractController
{
    #[Route(name: 'app_user_api_index', methods: ['GET'])]
    #[isGranted('ROLE_ADMIN')]
    #[OA\Tag('Users')]
    #[OA\Response(
        response: 200,
        description: 'user list',
        content: new JsonContent(ref: '#/components/schemas/successUserList')
    )]
    #[OA\Response(
        response: 401,
        description: 'unauthorized',
        content: new JsonContent(ref: '#/components/schemas/error')
    )]
    #[OA\Response(
        response: 403,
        description: 'access denied',
        content: new JsonContent(ref: '#/components/schemas/error')
    )]
    public function index(UserRepository $userRepository): Response
    {
        return $this->json(['success' => true, 'data' => $userRepository->findAll()], context: ['groups' => ['list']]);
    }

    #[Route('/{id}', name: 'app_user_api_show', methods: ['GET'])]
    #[isGranted('ROLE_USER')]
    #[OA\Tag('Users')]
    #[OA\Response(
        response: 401,
        description: 'unauthorized',
        content: new JsonContent(ref: '#/components/schemas/error')
    )]
    #[OA\Response(
        response: 200,
        description: 'success',
        content: new JsonContent(ref: '#/components/schemas/successUser')
    )]
    #[OA\Response(
        response: 403,
        description: 'access denied',
        content: new JsonContent(ref: '#/components/schemas/error')
    )]
    #[OA\Response(
        response: 404,
        description: 'not found',
        content: new JsonContent(ref: '#/components/schemas/notFound')
    )]
    public function show(User $user): Response
    {
        $this->checkAccess($user);

        return $this->json(['success'=>true,'data'=>$user], context: ['groups' => ['show']]);
    }

    #[Route('/{id}/edit', name: 'app_user_api_edit', methods: ['PUT'])]
    #[OA\Tag('Users')]
    #[OA\Response(
        response: 401,
        description: 'unauthorized',
        content: new JsonContent(ref: '#/components/schemas/error')
    )]
    #[OA\Response(
        response: 403,
        description: 'access denied',
        content: new JsonContent(ref: '#/components/schemas/error')
    )]
    #[OA\Response(
        response: 200,
        description: 'success',
        content: new JsonContent(ref: '#/components/schemas/successUser')
    )]
    #[OA\Response(
        response: 422,
        description: 'validation fail',
        content: new JsonContent(ref: '#/components/schemas/validationFailed')
    )]
    #[OA\Response(
        response: 404,
        description: 'not found',
        content: new JsonContent(ref: '#/components/schemas/notFound')
    )]
    public function edit(
        #[MapRequestPayload(serializationContext: ['groups'=>['update']])] UserDto $userDto,
        User $user,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        $this->checkAccess($user);
        $user->updateFromDto($userDto);

        if (isset($userDto->password)) {
            $user->setPassword($passwordHasher->hashPassword($user, $userDto->password));
        }

        $entityManager->flush();

        return $this->json(['success' => true,'data' => $user], context: ['groups' => ['show']]
        );
    }

    #[Route('/{id}', name: 'app_user_api_delete', methods: ['DELETE'])]
    #[isGranted('ROLE_ADMIN')]
    #[OA\Tag('Users')]
    #[OA\Response(
        response: 401,
        description: 'unauthorized',
        content: new JsonContent(ref: '#/components/schemas/error')
    )]
    #[OA\Response(
        response: 403,
        description: 'access denied',
        content: new JsonContent(ref: '#/components/schemas/error')
    )]
    #[OA\Response(
        response: 200,
        description: 'success',
        content: new JsonContent(ref: '#/components/schemas/successUserDelete')
    )]
    #[OA\Response(
        response: 404,
        description: 'not found',
        content: new JsonContent(ref: '#/components/schemas/notFound')
    )]
    public function delete(User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() === $user) {
            throw $this->createAccessDeniedException();
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(['success' => true]);
    }

    private function checkAccess(User $user): void
    {
        if ($this->getUser() === $user || $this->isGranted('ROLE_ADMIN')) {
            return;
        }

        throw $this->createAccessDeniedException();
    }
}
