<?php

namespace App\Domain\Entity\User;

use App\Domain\ValueObject\User\Email;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class User
{
    private Email $email;

    private Collection $tasks;

    public function __construct(
        Email $email
    ) {
        $this->email = $email;
        $this->tasks = new ArrayCollection();
    }

    public static function register(Email $email): User
    {
        return new User($email);
    }

    public function getTasks(): Collection
    {
        return $this->tasks;
    }
}