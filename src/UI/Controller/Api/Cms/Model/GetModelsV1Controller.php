<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\Model;

use OpenApi\Annotations as OA;
use Skyeng\MarketingCmsBundle\Application\Cms\Model\Dto\ModelsRequest;
use Skyeng\MarketingCmsBundle\Application\Cms\Model\ModelService;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Skyeng\MarketingCmsBundle\Application\Service\Validator\ValidatorInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\ModelRepository\Exception\ModelNotFoundException;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Response\ResponseFactory;
use Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\Model\Validation\GetModelsV1Form;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetModelsV1Controller extends AbstractController
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    /**
     * Get Models paginated list.
     *
     * @OA\Tag(name="Marketing CMS")
     * @OA\Parameter(
     *     name="model",
     *     required=true,
     *     in="query",
     *     @OA\Schema(type="string"),
     *     description="Имя модели в конфиге (например page)",
     * ),
     * @OA\Parameter(
     *     name="filters",
     *     in="query",
     *     @OA\Schema(type="array", collectionFormat="multi", @OA\Items(type="string")),
     *     description="filters[foo]=bar",
     * ),
     * @OA\Parameter(
     *     name="sorts",
     *     in="query",
     *     @OA\Schema(type="array", collectionFormat="multi", @OA\Items(type="string")),
     *     description="sorts[bar]=ASC",
     * ),
     * @OA\Parameter(
     *     name="locale",
     *     in="query",
     *     @OA\Schema(type="string"),
     *     description="locale=en",
     * ),
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     @OA\Schema(type="integer", default="1")
     * ),
     * @OA\Parameter(
     *     name="per_page",
     *     in="query",
     *     @OA\Schema(type="integer", default="10")
     * ),
     * @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\Schema(
     *         type="object",
     *         @OA\Property(property="data", type="object", description="filds of model"),
     *         @OA\Property(
     *             property="meta",
     *             type="object",
     *             @OA\Property(property="total", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="current_page", type="integer"),
     *         ),
     *     )
     * )
     */
    #[Route(path: '/api/v1/cms/get-models', methods: ['GET'])]
    public function index(Request $request, ModelService $modelService): Response
    {
        $modelsRequest = new ModelsRequest();

        try {
            $this->validator->validate(GetModelsV1Form::class, $request->query->all(), $modelsRequest);

            $response = $modelService->filterModels($modelsRequest);
        } catch (ValidationException $exception) {
            return ResponseFactory::createErrorResponse($exception->getMessage(), $exception->getErrors());
        } catch (ModelNotFoundException) {
            return ResponseFactory::createErrorResponse('Page not found', []);
        }

        return ResponseFactory::createPaginatedOkResponse(
            $response->modelSlice,
            $response->total,
            $modelsRequest->page,
            $modelsRequest->perPage
        );
    }
}
