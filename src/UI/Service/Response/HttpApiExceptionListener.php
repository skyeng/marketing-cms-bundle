<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Service\Response;

use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class HttpApiExceptionListener
{
    /**
     * @param ExceptionEvent $event
     * @throws Throwable
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($event->getRequest() instanceof Request) {
            if ($exception instanceof ValidationException) {
                $response = ResponseFactory::createErrorResponse(
                    $event->getRequest(),
                    $exception->getMessage(),
                    $exception->getErrors()
                );
            } elseif ($exception instanceof HttpException) {
                $response = ResponseFactory::createErrorResponse(
                    $event->getRequest(),
                    $exception->getMessage(),
                    [],
                    $exception->getStatusCode()
                );
            } elseif ($exception->getPrevious() instanceof HttpException) {
                $response = ResponseFactory::createErrorResponse(
                    $event->getRequest(),
                    $exception->getPrevious()->getMessage(),
                    [],
                    $exception->getPrevious()->getStatusCode()
                );
            } else {
                throw $exception;
            }
        } else {
            throw $exception;
        }

        $event->setResponse($response);
    }
}
