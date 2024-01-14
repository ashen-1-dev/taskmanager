<?php

namespace App\EventListener;

use App\Exception\BusinessLogicException;
use App\Exception\ValidationException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    const VALIDATION_EXCEPTION = 'validation_exception';
    const BUSINESS_LOGIC_EXCEPTION = 'business_logic_exception';
    const UNEXPECTED_ERROR = 'unexpected_error';


    public function __construct(
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $isDebug = $this->parameterBag->get('kernel.debug');

        if ($exception instanceof ValidationException) {
            $response = $this->handleValidationException($exception);
            $event->setResponse($response);
        } elseif ($exception instanceof BusinessLogicException) {
            $response = $this->handleBusinessLogicException($exception);
            $event->setResponse($response);
        } elseif(!$isDebug) {
            $response = new JsonResponse(
                ['message' => 'Something went wrong', 'error_type' => self::UNEXPECTED_ERROR],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
            $event->setResponse($response);
        }
    }

    private function handleValidationException(ValidationException $exception): Response
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

    private function handleBusinessLogicException(BusinessLogicException $exception): Response
    {
        return new JsonResponse(
            ['message' => $exception->getUserMessage(), 'error_type' => self::BUSINESS_LOGIC_EXCEPTION],
            Response::HTTP_BAD_REQUEST
        );
    }
}