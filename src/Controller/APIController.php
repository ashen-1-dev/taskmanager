<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class APIController extends AbstractController
{
    public function responseOK(mixed $data = null): JsonResponse
    {
        return $this->responseJson($data, Response::HTTP_OK);
    }

    public function responseCreated(mixed $data = null): JsonResponse
    {
        return $this->responseJson($data, Response::HTTP_CREATED);
    }

    public function responseJson(mixed $data = null, int $httpCode): JsonResponse
    {
        return $this->json(['data' => $data], $httpCode);
    }
}