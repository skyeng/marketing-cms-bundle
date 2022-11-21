<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\MediaFile;

use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Dto\RemoveMediaFiles\RemoveMediaFilesV1RequestDto;
use Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\MediaFileService;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Skyeng\MarketingCmsBundle\Application\Service\Validator\ValidatorInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Security\Editor\EditorSecurityServiceInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Security\Exception\AccessDeniedException;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Response\ResponseFactory;
use Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\MediaFile\Validation\RemoveMediaFilesV1Form;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RemoveMediaFilesV1Controller extends AbstractController
{
    public function __construct(
        private MediaFileService $mediaFileService,
        private ValidatorInterface $validator,
        private EditorSecurityServiceInterface $editorSecurityService
    ) {
    }

    /**
     * Удалить медиа файлы.
     *
     * @OA\Tag(name="Marketing CMS"),
     * @OA\RequestBody(
     *     @OA\MediaType(
     *          mediaType="application/x-www-form-urlencoded",
     *          @OA\Schema(
     *              type="object",
     *              ref=@Model(type=RemoveMediaFilesV1RequestDto::class),
     *          )
     *     )
     * ),
     * @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\Schema(
     *         type="object",
     *         allOf={
     *             @OA\Schema(ref="#/components/schemas/MarketingCmsJsonResponseOk")
     *         }
     *     )
     * ),
     * @OA\Response(response=400, description="Bad Request", @OA\Schema(ref="#/components/schemas/MarketingCmsJsonResponseError")),
     * @OA\Response(response=500, description="Internal Server Error", @OA\Schema(ref="#/components/schemas/MarketingCmsJsonResponseException")),
     */
    #[Route(path: '/api/v1/cms/remove-media-files', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        try {
            $this->editorSecurityService->denyAccessIfRequired();

            $dto = new RemoveMediaFilesV1RequestDto();

            $this->validator->validate(RemoveMediaFilesV1Form::class, $request->request->all(), $dto);

            $this->mediaFileService->removeFiles($dto);

            return ResponseFactory::createOkResponse([], 'success');
        } catch (AccessDeniedException $e) {
            return ResponseFactory::createErrorResponse($e->getMessage(), [], Response::HTTP_FORBIDDEN);
        } catch (ValidationException $e) {
            return ResponseFactory::createErrorResponse($e->getMessage(), $e->getErrors());
        } catch (Exception $e) {
            return ResponseFactory::createErrorResponse($e->getMessage(), [], $e->getCode());
        }
    }
}
