<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\Template;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Skyeng\MarketingCmsBundle\Application\Cms\Template\Dto\GetTemplatesV1ResultDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Template\TemplateService;
use Skyeng\MarketingCmsBundle\Domain\Service\Security\Editor\EditorSecurityServiceInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Security\Exception\AccessDeniedException;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Response\ResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class GetTemplatesV1Controller extends AbstractController
{
    public function __construct(
        private TemplateService $templateService,
        private EditorSecurityServiceInterface $editorSecurityService
    ) {
    }

    /**
     * Получить все готовые компоненты.
     *
     * @OA\Tag(name="Marketing CMS"),
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
     *              ref=@Model(type=GetTemplatesV1ResultDto::class)
     *          )
     *     )
     * ),
     * @OA\Response(response=500, description="Internal Server Error", @OA\Schema(ref="#/components/schemas/MarketingCmsJsonResponseException")),
     */
    #[Route(path: '/api/v1/cms/get-templates', methods: ['GET'])]
    public function __invoke(): Response
    {
        try {
            $this->editorSecurityService->denyAccessIfRequired();

            $result = $this->templateService->getTemplates();

            return ResponseFactory::createOkResponse($result);
        } catch (AccessDeniedException $e) {
            return ResponseFactory::createErrorResponse($e->getMessage(), [], Response::HTTP_FORBIDDEN);
        } catch (Throwable $e) {
            return ResponseFactory::createErrorResponse($e->getMessage(), [], $e->getCode());
        }
    }
}
