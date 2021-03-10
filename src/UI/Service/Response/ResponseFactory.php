<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Service\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;
use Throwable;

class ResponseFactory
{
    /**
     * @param Request $request
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return Response
     */
    public static function createOkResponse(
        Request $request,
        $data = [],
        string $message = '',
        int $code = Response::HTTP_OK
    ): Response {
        $formats = $request->headers->get('content-type');

        switch ($formats) {
            default:
                $response = static::createJsonResponse(
                    [
                        'message' => $message,
                        'data' => $data,
                    ],
                    $code
                );
        }

        return $response;
    }

    public static function createErrorResponse(
        Request $request,
        string $message = '',
        array $errors = [],
        int $code = Response::HTTP_BAD_REQUEST
    ): Response {
        $formats = $request->headers->get('content-type');

        switch ($formats) {
            default:
                $response = static::createJsonResponse(
                    [
                        'message' => $message,
                        'errors' => $errors,
                    ],
                    $code
                );
        }

        return $response;
    }

    public static function createExceptionResponse(
        Request $request,
        string $message,
        string $exceptionMessage,
        ?Throwable $exception = null,
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR
    ): Response {
        $formats = $request->headers->get('content-type');

        switch ($formats) {
            default:
                $data = [
                    'message' => $message,
                    'exceptionMessage' => $exceptionMessage,
                ];
                if (null !== $exception) {
                    $data['exceptionType'] = get_class($exception);
                    $data['stackTrace'] = $exception->getTrace();
                }
                $response = static::createJsonResponse($data, $code);
        }

        return $response;
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
        $response->setMaxAge((int)$cacheTime);

        return $response;
    }

    public static function createRedirectResponse(
        string $targetUrl,
        int $httpCode = 301
    ): Response {
        return new RedirectResponse($targetUrl, $httpCode);
    }

    /**
     * @param mixed $data
     * @param int $code
     * @return JsonResponse
     */
    protected static function createJsonResponse(
        $data,
        int $code
    ): JsonResponse {
        return new JsonResponse($data, $code);
    }
}
