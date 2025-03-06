<?php

namespace App\Entity\Dto;

use OpenApi\Attributes\Property;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class UserDto
{
    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Email(groups: ['create','Default'])]
    #[Property(
        description: 'Электронная почта',
        type: 'string',
        example: 'john@gmail.com'
    )]
    #[Groups(['create', 'update', 'show', 'list', 'login'])]
    public string $email;

    #[Assert\Length(min: 8, groups: ['create','Default'])]
    #[Assert\NotBlank(groups: ['create'])]
    #[Property(
        description: 'Пароль',
        type: 'string',
        minLength: 8,
        example: 'youBestPass'
    )]
    #[Groups(['create', 'update', 'login'])]
    public string $password;

    #[Assert\NotBlank(groups: ['create'])]
    #[Property(
        description: 'Имя',
        type: 'string',
        minLength: 1,
        example: 'Петр'
    )]
    #[Groups(['create', 'update', 'show'])]
    public string $firstname;

    #[Assert\NotBlank(groups: ['create'])]
    #[Property(
        description: 'Фамилия',
        type: 'string',
        minLength: 1,
        example: 'Петров'
    )]
    #[Groups(['create', 'update', 'show'])]
    public string $lastname;
}