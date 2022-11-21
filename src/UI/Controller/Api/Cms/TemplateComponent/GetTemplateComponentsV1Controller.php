<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\TemplateComponent;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent\Dto\Get\GetTemplateComponentsV1RequestDto;
use Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent\Dto\Get\GetTemplateComponentsV1ResultDto;
use Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent\TemplateComponentService;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Skyeng\MarketingCmsBundle\Application\Service\Validator\ValidatorInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\Exception\TemplateNotFoundException;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Response\ResponseFactory;
use Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\TemplateComponent\Validation\GetTemplateComponentsV1Form;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class GetTemplateComponentsV1Controller extends AbstractController
{
    public function __construct(
        private TemplateComponentService $templateComponentService,
        private ValidatorInterface $validator
    ) {
    }

    /**
     * Получить готовый компонент с вложенными компонентами.
     *
     * @OA\Tag(name="Marketing CMS"),
     * @OA\Parameter(
     *     name="templateId",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string"),
     *     description="Id готового компонента",
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
     *              ref=@Model(type=GetTemplateComponentsV1ResultDto::class)
     *          )
     *     )
     * ),
     * @OA\Response(response=400, description="Bad Request", @OA\Schema(ref="#/components/schemas/MarketingCmsJsonResponseError")),
     * @OA\Response(response=500, description="Internal Server Error", @OA\Schema(ref="#/components/schemas/MarketingCmsJsonResponseException")),
     */
    #[Route(path: '/api/v1/cms/get-template-components', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        try {
            $dto = new GetTemplateComponentsV1RequestDto();
            $this->validator->validate(GetTemplateComponentsV1Form::class, $request->query->all(), $dto);
            $result = $this->templateComponentService->getTemplateComponents($dto);

            return ResponseFactory::createOkResponse($result);
        } catch (ValidationException $e) {
            return ResponseFactory::createErrorResponse($e->getMessage(), $e->getErrors());
        } catch (TemplateNotFoundException $e) {
            return ResponseFactory::createErrorResponse('Template not found', []);
        } catch (Throwable $e) {
            return ResponseFactory::createErrorResponse($e->getMessage(), [], $e->getCode());
        }
    }
}
