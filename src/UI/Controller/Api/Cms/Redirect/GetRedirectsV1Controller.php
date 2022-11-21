<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\Redirect;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Skyeng\MarketingCmsBundle\Application\Cms\Redirect\RedirectService;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Response\ResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetRedirectsV1Controller extends AbstractController
{
    public function __construct(private RedirectService $redirectService)
    {
    }

    /**
     * Получить все редиректы.
     *
     * @OA\Tag(name="Marketing CMS"),
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
     *                     ref=@Model(type=\Skyeng\MarketingCmsBundle\Application\Cms\Redirect\Dto\GetRedirectsV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @OA\Response(response=400, description="Bad Request", @OA\Schema(ref="#/components/schemas/MarketingCmsJsonResponseError")),
     * @OA\Response(response=500, description="Internal Server Error", @OA\Schema(ref="#/components/schemas/MarketingCmsJsonResponseException")),
     *
     * @throws ValidationException
     */
    #[Route(path: '/api/v1/cms/get-redirects', methods: ['GET'])]
    public function __invoke(): Response
    {
        $result = $this->redirectService->getRedirects();

        return ResponseFactory::createOkResponse($result);
    }
}
