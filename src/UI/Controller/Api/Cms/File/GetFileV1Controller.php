<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\File;

use Skyeng\MarketingCmsBundle\Application\Cms\File\Exception\FileRedirectException;
use Skyeng\MarketingCmsBundle\Application\Cms\File\FileService;
use Skyeng\MarketingCmsBundle\Application\Cms\File\Dto\GetFileV1RequestDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Redirect\RedirectService;
use Skyeng\MarketingCmsBundle\Domain\Repository\FileRepository\Exception\FileNotFoundException;
use Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\File\Validation\GetFileV1Form;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Skyeng\MarketingCmsBundle\UI\Service\Validator\ValidatorInterface;
use Skyeng\MarketingCmsBundle\UI\Service\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

class GetFileV1Controller extends AbstractController
{
    /**
     * @var RedirectService
     */
    private $redirectService;

    /**
     * @var FileService
     */
    private $fileService;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        RedirectService $redirectService,
        FileService $fileService,
        ValidatorInterface $validator
    ) {
        $this->redirectService = $redirectService;
        $this->fileService = $fileService;
        $this->validator = $validator;
    }

    /**
     * Cms File
     *
     * @SWG\Tag(name="Marketing CMS"),
     * @SWG\Parameter(
     *     name="uri",
     *     in="query",
     *     required=true,
     *     type="string",
     *     description="Uri путь (например /file.txt)",
     * ),
     * @SWG\Response(
     *     response=200,
     *     description="OK (Динамический формат ответа)",
     *     @SWG\Schema(type="file"),
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/MarketingCmsJsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/MarketingCmsJsonResponseException")),
     *
     * @Route("/api/v1/cms/get-file", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new GetFileV1RequestDto();

        try {
            $this->validator->validate(GetFileV1Form::class, $request->query->all(), $dto);
        } catch (ValidationException $exception) {
            return ResponseFactory::createErrorResponse($request, $exception->getMessage(), $exception->getErrors());
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
            return ResponseFactory::createErrorResponse($request, 'File resource not found', []);
        } catch (FileRedirectException $exception) {
            return ResponseFactory::createRedirectResponse($exception->getTargetUrl(), $exception->getHttpCode());
        }
    }
}
