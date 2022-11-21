<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\ModelComponent;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Skyeng\MarketingCmsBundle\Application\Cms\Component\ComponentService;
use Skyeng\MarketingCmsBundle\Application\Cms\Component\Dto\Get\GetModelComponentsV1RequestDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Component\Dto\Get\GetModelComponentsV1ResultDto;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Skyeng\MarketingCmsBundle\Application\Service\Validator\ValidatorInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\ModelRepository\Exception\ModelNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Service\Security\Editor\EditorSecurityServiceInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Security\Exception\AccessDeniedException;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Response\ResponseFactory;
use Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\ModelComponent\Validation\GetModelComponentsV1Form;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Throwable;

final class GetModelComponentsV1Controller extends AbstractController
{
    public function __construct(
        private ValidatorInterface $validator,
        private ComponentService $componentService,
        private EditorSecurityServiceInterface $editorSecurityService
    ) {
    }

    /**
     * Получить все компоненты модели.
     *
     * @OA\Tag(name="Marketing CMS"),
     * @OA\Parameter(
     *     name="modelId",
     *     in="query",
     *     required=true,
     *     description="Id модели",
     * ),
     * @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *          @OA\Property(
     *              type="string",
     *              property="message"
     *          ),
     *          @OA\Property(
     *              property="data",
     *              ref=@Model(type=GetModelComponentsV1ResultDto::class)
     *          )
     *     )
     * ),
     * @OA\Response(response=400, description="Bad Request", @OA\Schema(ref="#/components/schemas/MarketingCmsJsonResponseError")),
     * @OA\Response(response=500, description="Internal Server Error", @OA\Schema(ref="#/components/schemas/MarketingCmsJsonResponseException")),
     */
    #[Route(path: '/api/v1/cms/get-model-components', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        try {
            $this->editorSecurityService->denyAccessIfRequired();

            $dto = new GetModelComponentsV1RequestDto();

            $this->validator->validate(GetModelComponentsV1Form::class, $request->query->all(), $dto);

            $result = $this->componentService->getComponentsByModel($dto);

            return ResponseFactory::createOkResponse($result);
        } catch (AccessDeniedException|AuthenticationException $e) {
            return ResponseFactory::createErrorResponse($e->getMessage(), [], Response::HTTP_FORBIDDEN);
        } catch (ValidationException $e) {
            return ResponseFactory::createErrorResponse($e->getMessage(), $e->getErrors());
        } catch (ModelNotFoundException $e) {
            return ResponseFactory::createErrorResponse('Model not found', []);
        } catch (Throwable $e) {
            $statusCode = $e->getCode() !== 0 ? $e->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

            return ResponseFactory::createErrorResponse($e->getMessage(), [], $statusCode);
        }
    }
}
