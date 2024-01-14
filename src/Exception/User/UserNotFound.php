<?php

namespace App\Exception\User;

use App\Exception\BusinessLogicException;

class UserNotFound extends BusinessLogicException
{
    public function __construct()
    {
        parent::__construct('User not found');
    }
}