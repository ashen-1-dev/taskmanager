<?php

namespace App\Service\Auth;

use App\Domain\Entity\User\User;
use App\Domain\ValueObject\User\Email;
use App\Domain\ValueObject\User\Password;
use App\Exception\ValidationException;
use App\Service\Auth\AuthServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $hasher
    ) {
    }

    public function register(Email $email, Password $password): User
    {
        $user = User::register(Uuid::v4(), $email, $password);

        $hashedPassword = new Password($this->hasher->hashPassword($user, $password->toString()));
        $user->setPassword($hashedPassword);

        $errors = $this->validator->validate($user);

        if ($errors->count()) {
            throw ValidationException::withMessages($errors);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}