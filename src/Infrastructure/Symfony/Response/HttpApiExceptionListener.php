<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Response;

use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class HttpApiExceptionListener
{
    /**
     * @throws Throwable
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ValidationException) {
            $response = ResponseFactory::createErrorResponse(
                $exception->getMessage(),
                $exception->getErrors()
            );
        } elseif ($exception instanceof HttpException) {
            $response = ResponseFactory::createErrorResponse(
                $exception->getMessage(),
                []
            );
        } elseif ($exception->getPrevious() instanceof HttpException) {
            $response = ResponseFactory::createErrorResponse(
                $exception->getPrevious()->getMessage(),
                []
            );
        } else {
            throw $exception;
        }

        $event->setResponse($response);
    }
}
