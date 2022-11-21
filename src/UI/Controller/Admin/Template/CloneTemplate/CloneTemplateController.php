<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Admin\Template\CloneTemplate;

use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Skyeng\MarketingCmsBundle\Application\Cms\Template\Dto\CloneTemplate\CloneTemplateRequestDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Template\TemplateService;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Skyeng\MarketingCmsBundle\Application\Service\Validator\ValidatorInterface;
use Skyeng\MarketingCmsBundle\Domain\Factory\Template\Exception\TemplateCannotBeClonedException;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\Exception\TemplateNotFoundException;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Response\ResponseFactory;
use Skyeng\MarketingCmsBundle\UI\Controller\Admin\Template\CloneTemplate\Validation\CloneTemplateForm;
use Skyeng\MarketingCmsBundle\UI\Controller\Admin\TemplateCrudController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CloneTemplateController extends AbstractController
{
    public function __construct(
        private TemplateService $templateService,
        private ValidatorInterface $validator,
        private AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    /**
     * Склонировать готовый компонент
     */
    #[Route(path: '/admin/template/clone', name: 'clone_template', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $dto = new CloneTemplateRequestDto();

        try {
            $queryParams = $request->query->all()['routeParams'] ?? [];
            $this->validator->validate(CloneTemplateForm::class, $queryParams, $dto);
        } catch (ValidationException $exception) {
            return ResponseFactory::createErrorResponse($exception->getMessage(), $exception->getErrors());
        }

        try {
            $result = $this->templateService->cloneTemplate($dto);
            $this->addFlash('success', 'Готовый компонент успешно склонирован');

            return ResponseFactory::createRedirectResponse($result->url);
        } catch (TemplateNotFoundException) {
            return ResponseFactory::createErrorResponse('Готовый компонент не найден', [], Response::HTTP_NOT_FOUND);
        } catch (TemplateCannotBeClonedException) {
            $url = $this->adminUrlGenerator
                ->setController(TemplateCrudController::class)
                ->setAction('index')
                ->generateUrl();
            $this->addFlash('error', 'Произошла ошибка во время клонирования готового компонента');

            return ResponseFactory::createRedirectResponse($url);
        }
    }
}
