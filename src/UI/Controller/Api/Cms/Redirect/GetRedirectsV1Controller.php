<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\Redirect;

use Skyeng\MarketingCmsBundle\Application\Cms\Redirect\RedirectService;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Skyeng\MarketingCmsBundle\UI\Service\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class GetRedirectsV1Controller extends AbstractController
{
    /**
     * @var RedirectService
     */
    private $redirectService;

    public function __construct(RedirectService $redirectService)
    {
        $this->redirectService = $redirectService;
    }

    /**
     * Получить все редиректы
     *
     * @SWG\Tag(name="Marketing CMS"),
     * @SWG\Response(
     *     response=200,
     *     description="OK",
     *     @SWG\Schema(
     *         type="object",
     *         allOf={
     *             @SWG\Schema(ref="#/definitions/MarketingCmsJsonResponseOk"),
     *             @SWG\Schema(
     *                 @SWG\Property(
     *                     property="data",
     *                     ref=@Model(type=\Skyeng\MarketingCmsBundle\Application\Cms\Redirect\Dto\GetRedirectsV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/MarketingCmsJsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/MarketingCmsJsonResponseException")),
     *
     * @Route("/api/v1/cms/get-redirects", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $result = $this->redirectService->getRedirects();
        return ResponseFactory::createOkResponse($request, $result);
    }
}
