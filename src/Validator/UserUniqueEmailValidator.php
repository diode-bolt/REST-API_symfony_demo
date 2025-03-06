<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class UserUniqueEmailValidator extends ConstraintValidator
{
    public function __construct(private UserRepository $repository)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var UserUniqueEmail $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if (!$this->repository->findOneBy(['email'=> $value])) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation()
        ;
    }
}
