<?php

namespace App\Service\Auth;

use App\Domain\Entity\User\User;
use App\Domain\ValueObject\User\Email;
use App\Domain\ValueObject\User\Password;

interface AuthServiceInterface
{
    public function register(Email $email, Password $password): User;
}