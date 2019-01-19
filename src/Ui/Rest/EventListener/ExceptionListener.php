<?php

declare(strict_types=1);

namespace App\Ui\Rest\EventListener;

use App\Application\Exception\ProvidesHttpStatusCode;
use App\Ui\Rest\Exception\FormValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        $exception = $event->getException();

        if ($exception instanceof HttpException) {
            $code = $exception->getStatusCode();
            $message = $exception->getMessage();
            $responseData = [
                'code' => $exception->getStatusCode(),
                'message' => $message,
            ];
        } elseif ($exception instanceof FormValidationException) {
            $code = 400;
            $message = 'Form validation error';
            $responseData = [
                'code' => 400,
                'message' => $message,
                'errors' => $exception->getErrors(),
            ];
        } else {
            $code = $this->getStatusCode($exception);
            $message = $this->getStatusMessage($exception);
            $responseData = [
                'code' => $code,
                'message' => $message,
            ];
        }

        $event->setResponse(new JsonResponse($responseData, $code));
    }

    private function getStatusCode(\Exception $exception): int
    {
        return $exception instanceof ProvidesHttpStatusCode ? $exception->getHttpStatusCode() : 500;
    }

    private function getStatusMessage(\Exception $exception): string
    {
        // @TODO Invalid server error when debug mode is disabled
        return $exception->getMessage();
    }
}
