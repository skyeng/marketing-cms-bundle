<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\MediaFile;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Dto\AddMediaFile\AddMediaFileV1RequestDto;
use Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\MediaFileService;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Skyeng\MarketingCmsBundle\Application\Service\Validator\ValidatorInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Security\Editor\EditorSecurityServiceInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Security\Exception\AccessDeniedException;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Response\ResponseFactory;
use Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\MediaFile\Validation\AddMediaFileV1Form;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class AddMediaFileV1Controller extends AbstractController
{
    public function __construct(
        private MediaFileService $mediaFileService,
        private ValidatorInterface $validator,
        private EditorSecurityServiceInterface $editorSecurityService
    ) {
    }

    /**
     * Добавление медиа файла.
     *
     * @OA\Tag(name="Marketing CMS"),
     * @OA\RequestBody(
     *     content={
     *     @OA\MediaType(mediaType="multipart/form-data")
     * }),
     * @OA\Parameter(
     *     name="file",
     *     required=true,
     *     parameter="file",
     *     in="header",
     *     @OA\Schema(type="file"),
     *     description="Изображение"
     * ),
     * @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\Schema(
     *         type="object",
     *         allOf={
     *             @OA\Schema(ref="#/components/schemas/MarketingCmsJsonResponseOk"),
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="data",
     *                     ref=@Model(type=\Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Dto\AddMediaFile\AddMediaFileV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @OA\Response(response=400, description="Bad Request", @OA\Schema(ref="#/components/schemas/MarketingCmsJsonResponseError")),
     * @OA\Response(response=500, description="Internal Server Error", @OA\Schema(ref="#/components/schemas/MarketingCmsJsonResponseException")),
     */
    #[Route(path: '/api/v1/cms/add-media-file', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        try {
            $this->editorSecurityService->denyAccessIfRequired();

            $dto = new AddMediaFileV1RequestDto();

            $this->validator->validate(
                AddMediaFileV1Form::class,
                ['file' => $request->files->get('file')],
                $dto,
            );

            $result = $this->mediaFileService->addMediaFile($dto);

            return ResponseFactory::createOkResponse($result);
        } catch (AccessDeniedException $e) {
            return ResponseFactory::createErrorResponse($e->getMessage(), [], Response::HTTP_FORBIDDEN);
        } catch (ValidationException $e) {
            return ResponseFactory::createErrorResponse($e->getMessage(), $e->getErrors());
        } catch (Throwable $e) {
            return ResponseFactory::createErrorResponse($e->getMessage(), [], 500);
        }
    }
}
