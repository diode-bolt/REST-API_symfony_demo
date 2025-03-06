<?php

namespace App\Entity\Dto;

use OpenApi\Attributes\Property;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class UserDto
{
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Property(
        description: 'Электронная почта',
        type: 'string',
    )]
    #[Groups(['create', 'update', 'show', 'list', 'login'])]
    public string $email;

    #[Assert\Length(min: 8)]
    #[Assert\NotBlank]
    #[Property(
        description: 'Пароль',
        type: 'string',
        minLength: 8
    )]
    #[Groups(['create', 'update', 'login'])]
    public string $password;

    #[Assert\NotBlank]
    #[Property(
        description: 'Имя',
        type: 'string',
        minLength: 1
    )]
    #[Groups(['create', 'update', 'show'])]
    public string $firstname;

    #[Assert\NotBlank]
    #[Property(
        description: 'Фамилия',
        type: 'string',
        minLength: 1
    )]
    #[Groups(['create', 'update', 'show'])]
    public string $lastname;
}