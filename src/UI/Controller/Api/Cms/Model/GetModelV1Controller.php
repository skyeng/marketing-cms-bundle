<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\Model;

use OpenApi\Annotations as OA;
use Skyeng\MarketingCmsBundle\Application\Cms\Model\Dto\ModelRequest;
use Skyeng\MarketingCmsBundle\Application\Cms\Model\ModelService;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Skyeng\MarketingCmsBundle\Application\Service\Validator\ValidatorInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\ModelRepository\Exception\ModelNotFoundException;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Response\ResponseFactory;
use Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\Model\Validation\GetModelV1Form;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetModelV1Controller extends AbstractController
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    /**
     * Get Model by ID.
     *
     * @OA\Tag(name="Marketing CMS")
     * @OA\Parameter(
     *     name="id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(type="string"),
     *     description="ID Модели",
     * ),
     * @OA\Parameter(
     *     name="locale",
     *     in="query",
     *     @OA\Schema(type="string"),
     *     description="locale=en",
     * ),
     * @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\Schema(type="object")
     * )
     */
    #[Route(path: '/api/v1/cms/get-model', methods: ['GET'])]
    public function show(Request $request, ModelService $modelService): Response
    {
        $modelRequest = new ModelRequest();

        try {
            $this->validator->validate(GetModelV1Form::class, $request->query->all(), $modelRequest);
            $data = $modelService->getModel($modelRequest);
        } catch (ValidationException $exception) {
            return ResponseFactory::createErrorResponse($exception->getMessage(), $exception->getErrors());
        } catch (ModelNotFoundException) {
            return ResponseFactory::createErrorResponse('Page not found', []);
        }

        return ResponseFactory::createOkResponse($data);
    }
}
