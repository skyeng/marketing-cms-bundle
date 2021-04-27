<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Admin\Page\ClonePage;

use App\Controller\Admin\ArticleCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Skyeng\MarketingCmsBundle\Application\Pages\ClonePage\ClonePageService;
use Skyeng\MarketingCmsBundle\Application\Pages\ClonePage\Dto\ClonePageRequestDto;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageRepository\Exception\PageNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Service\ClonePageService\Exception\ClonePageServiceException;
use Skyeng\MarketingCmsBundle\UI\Controller\Admin\Page\ClonePage\Validation\ClonePageForm;
use Skyeng\MarketingCmsBundle\UI\Controller\Admin\PageCrudController;
use Skyeng\MarketingCmsBundle\UI\Service\Response\ResponseFactory;
use Skyeng\MarketingCmsBundle\UI\Service\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClonePageController extends AbstractController
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var ClonePageService
     */
    private $clonePageService;

    /**
     * @var AdminUrlGenerator
     */
    private $adminUrlGenerator;

    public function __construct(
        ClonePageService $clonePageService,
        ValidatorInterface $validator,
        AdminUrlGenerator $adminUrlGenerator
    ) {
        $this->validator = $validator;
        $this->clonePageService = $clonePageService;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    /**
     * Удалить статью
     *
     * @Route("/admin/page/clone", name="clone_page", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $dto = new ClonePageRequestDto();

        try {
            $queryParams = $request->query->all()['routeParams'] ?? [];
            $this->validator->validate(ClonePageForm::class, $queryParams, $dto);
        } catch (ValidationException $exception) {
            return ResponseFactory::createErrorResponse($request, $exception->getMessage(), $exception->getErrors());
        }

        try {
            $result = $this->clonePageService->clonePage($dto);
            $this->addFlash('success', 'Страница успешно склонирована');
            return ResponseFactory::createRedirectResponse($result->url);
        } catch (PageNotFoundException $exception) {
            return ResponseFactory::createErrorResponse($request, 'Страница не найдена', [], Response::HTTP_NOT_FOUND);
        } catch (ClonePageServiceException $e) {
            $url = $this->adminUrlGenerator
                ->setController(PageCrudController::class)
                ->setAction('index')
                ->generateUrl();
            $this->addFlash('error', 'Произошла ошибка во время клонирования страницы');
            return ResponseFactory::createRedirectResponse($url);
        }
    }
}
