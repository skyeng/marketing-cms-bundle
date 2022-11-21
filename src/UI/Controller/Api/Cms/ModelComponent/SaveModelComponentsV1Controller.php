<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\ModelComponent;

use JsonException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Skyeng\MarketingCmsBundle\Application\Cms\Component\ComponentService;
use Skyeng\MarketingCmsBundle\Application\Cms\Component\Dto\Save\SaveModelComponentsV1RequestDto;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Skyeng\MarketingCmsBundle\Application\Service\Validator\ValidatorInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Security\Editor\EditorSecurityServiceInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Security\Exception\AccessDeniedException;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Response\ResponseFactory;
use Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\ModelComponent\Validation\SaveModelComponentsV1Form;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Throwable;

final class SaveModelComponentsV1Controller extends AbstractController
{
    public function __construct(
        private ComponentService $componentService,
        private ValidatorInterface $validator,
        private EditorSecurityServiceInterface $editorSecurityService
    ) {
    }

    /**
     * Сохранить компоненты модели.
     *
     * @OA\Tag(name="Marketing CMS"),
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *          ref=@Model(type=SaveModelComponentsV1RequestDto::class),
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
    #[Route(path: '/api/v1/cms/save-model-components', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        try {
            $this->editorSecurityService->denyAccessIfRequired();

            $data = json_decode($request->getContent(), true, 1024, \JSON_THROW_ON_ERROR);

            $dto = new SaveModelComponentsV1RequestDto();

            $this->validator->validate(SaveModelComponentsV1Form::class, $data, $dto);

            $this->componentService->saveComponentsByModel($dto);

            return ResponseFactory::createOkResponse([], 'success');
        } catch (AccessDeniedException|AuthenticationException $e) {
            return ResponseFactory::createErrorResponse($e->getMessage(), [], Response::HTTP_FORBIDDEN);
        } catch (JsonException $e) {
            return ResponseFactory::createErrorResponse('Could not deserialize request', [
                'error' => $e->getMessage(),
            ]);
        } catch (ValidationException $e) {
            return ResponseFactory::createErrorResponse($e->getMessage(), $e->getErrors());
        } catch (Throwable $e) {
            return ResponseFactory::createErrorResponse($e->getMessage(), [], 500);
        }
    }
}
