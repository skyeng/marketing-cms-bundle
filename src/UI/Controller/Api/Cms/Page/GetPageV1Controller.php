<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\Page;

use Skyeng\MarketingCmsBundle\Application\Cms\Page\PageService;
use Skyeng\MarketingCmsBundle\Application\Cms\Page\Dto\GetPageV1RequestDto;
use Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\Page\Validation\GetPageV1Form;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Skyeng\MarketingCmsBundle\UI\Service\Validator\ValidatorInterface;
use Skyeng\MarketingCmsBundle\UI\Service\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class GetPageV1Controller extends AbstractController
{
    /**
     * @var PageService
     */
    private $pageService;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(PageService $pageService, ValidatorInterface $validator)
    {
        $this->pageService = $pageService;
        $this->validator = $validator;
    }

    /**
     * Cms Page
     *
     * @SWG\Tag(name="CMS"),
     * @SWG\Parameter(
     *     name="uri",
     *     in="query",
     *     required=true,
     *     type="string",
     *     description="Uri путь (например /page)",
     * ),
     * @SWG\Response(
     *     response=200,
     *     description="OK",
     *     @SWG\Schema(
     *         type="object",
     *         allOf={
     *             @SWG\Schema(ref="#/definitions/JsonResponseOk"),
     *             @SWG\Schema(
     *                 @SWG\Property(
     *                     property="data",
     *                     ref=@Model(type=\Skyeng\MarketingCmsBundle\Application\Cms\Page\Dto\GetPageV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/cms/get-page", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new GetPageV1RequestDto();

        try {
            $this->validator->validate(GetPageV1Form::class, $request->query->all(), $dto);
        } catch (ValidationException $exception) {
            return ResponseFactory::createErrorResponse($request, $exception->getMessage(), $exception->getErrors());
        }

        $result = $this->pageService->getPage($dto);

        return ResponseFactory::createOkResponse($request, $result);
    }
}
