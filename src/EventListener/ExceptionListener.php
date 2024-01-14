<?php

namespace App\EventListener;

use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    const VALIDATION_EXCEPTION = 'validation_exception';
    const BUSINESS_LOGIC_EXCEPTION = 'business_logic_exception';
    const UNEXPECTED_ERROR = 'unexpected_error';


    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ValidationException) {
            $response = $this->handleValidationException($exception);
            $event->setResponse($response);
        } else {
            //TODO: do this in APP_ENV=prod otherwise return real exception
            $response = new JsonResponse(
                ['message' => 'Something went wrong', 'error_type' => self::UNEXPECTED_ERROR],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
            $event->setResponse($response);
        }
    }

    public function handleValidationException(ValidationException $exception): Response
    {
        $errors = [];
        foreach ($exception->getErrors() as $error) {
            $errors[$error->getPropertyPath()][] = $error->getMessage();
        }
        return new JsonResponse(
            ['message' => 'Validation error', 'error_type' => self::VALIDATION_EXCEPTION, 'errors' => $errors],
            $exception->getCode()
        );
    }
}