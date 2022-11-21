<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\File;

use OpenApi\Annotations as OA;
use Skyeng\MarketingCmsBundle\Application\Cms\File\Dto\GetFileV1RequestDto;
use Skyeng\MarketingCmsBundle\Application\Cms\File\Exception\FileRedirectException;
use Skyeng\MarketingCmsBundle\Application\Cms\File\FileService;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Skyeng\MarketingCmsBundle\Application\Service\Validator\ValidatorInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\FileRepository\Exception\FileNotFoundException;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Response\ResponseFactory;
use Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\File\Validation\GetFileV1Form;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetFileV1Controller extends AbstractController
{
    public function __construct(
        private FileService $fileService,
        private ValidatorInterface $validator
    ) {
    }

    /**
     * Cms File.
     *
     * @OA\Tag(name="Marketing CMS"),
     * @OA\Parameter(
     *     name="uri",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string"),
     *     description="Uri путь (например /file.txt)",
     * ),
     * @OA\Response(
     *     response=200,
     *     description="OK (Динамический формат ответа)",
     *     @OA\Schema(type="file"),
     * ),
     * @OA\Response(response=400, description="Bad Request", @OA\Schema(ref="#/components/schemas/MarketingCmsJsonResponseError")),
     * @OA\Response(response=500, description="Internal Server Error", @OA\Schema(ref="#/components/schemas/MarketingCmsJsonResponseException")),
     *
     * @throws ValidationException
     */
    #[Route(path: '/api/v1/cms/get-file', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $dto = new GetFileV1RequestDto();

        try {
            $this->validator->validate(GetFileV1Form::class, $request->query->all(), $dto);
        } catch (ValidationException $exception) {
            return ResponseFactory::createErrorResponse($exception->getMessage(), $exception->getErrors());
        }

        try {
            $result = $this->fileService->getFile($dto);

            return ResponseFactory::createFileResponse(
                $result->filename,
                $result->content,
                $result->contentType,
                $result->cacheTime,
            );
        } catch (FileNotFoundException $exception) {
            return ResponseFactory::createErrorResponse('File resource not found', []);
        } catch (FileRedirectException $exception) {
            return ResponseFactory::createRedirectResponse($exception->getTargetUrl(), $exception->getHttpCode());
        }
    }
}
