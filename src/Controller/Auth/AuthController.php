<?php

namespace App\Controller\Auth;

use App\Controller\APIController;
use App\Domain\ValueObject\User\Email;
use App\Domain\ValueObject\User\Password;
use App\Service\Auth\AuthServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends APIController
{
    #[Route('/register', name: 'register', methods: Request::METHOD_POST)]
    public function registerUser(AuthServiceInterface $authService, Request $request): JsonResponse
    {
        $payload = $request->getPayload();

        $email = new Email($payload->get('username'));
        $password = new Password($payload->get('password'));

        $user = $authService->register($email, $password);

        return $this->responseCreated(
            [
                'id' => $user->getId()->toRfc4122(),
                'email' => $user->getEmail()->toString(),
            ]
        );
    }
}