<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Response;

use Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Paginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;
use Throwable;

class ResponseFactory
{
    /**
     * @param mixed|array $data
     */
    public static function createOkResponse(
        $data = [],
        string $message = '',
        int $code = Response::HTTP_OK
    ): Response {
        return static::createJsonResponse(
            [
                'message' => $message,
                'data' => $data,
            ],
            $code
        );
    }

    public static function createPaginatedOkResponse(
        array $data = [],
        int $total = 0,
        int $currentPage = 1,
        int $perPage = Paginator::ITEMS_PER_PAGE,
        int $code = Response::HTTP_OK
    ): Response {
        return static::createJsonResponse(
            [
                'data' => $data,
                'meta' => [
                    'total' => $total,
                    'current_page' => $currentPage,
                    'per_page' => $perPage,
                ],
            ],
            $code
        );
    }

    public static function createErrorResponse(
        string $message = '',
        array $errors = [],
        int $code = Response::HTTP_BAD_REQUEST
    ): Response {
        return static::createJsonResponse(
            [
                'message' => $message,
                'errors' => $errors,
            ],
            $code
        );
    }

    public static function createExceptionResponse(
        string $message,
        string $exceptionMessage,
        ?Throwable $exception = null,
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR
    ): Response {
        $data = [
            'message' => $message,
            'exceptionMessage' => $exceptionMessage,
        ];

        if (null !== $exception) {
            $data['exceptionType'] = $exception::class;
            $data['stackTrace'] = $exception->getTrace();
        }

        return static::createJsonResponse($data, $code);
    }

    public static function createFileResponse(
        string $filename,
        string $content,
        string $contentType,
        string $cacheTime
    ): Response {
        $response = new Response($content);

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
        );

        $response->headers->set('Content-type', $contentType);
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true');
        $response->headers->addCacheControlDirective('must-revalidate', true);

        $response->setPublic();
        $response->setMaxAge((int) $cacheTime);

        return $response;
    }

    public static function createRedirectResponse(
        string $targetUrl,
        int $httpCode = 301
    ): Response {
        return new RedirectResponse($targetUrl, $httpCode);
    }

    protected static function createJsonResponse(
        mixed $data,
        int $code
    ): JsonResponse {
        return new JsonResponse($data, $code);
    }
}
