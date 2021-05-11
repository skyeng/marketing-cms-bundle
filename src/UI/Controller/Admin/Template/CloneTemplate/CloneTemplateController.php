<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Admin\Template\CloneTemplate;

use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Skyeng\MarketingCmsBundle\Application\Templates\CloneTemplate\CloneTemplateService;
use Skyeng\MarketingCmsBundle\Application\Templates\CloneTemplate\Dto\CloneTemplateRequestDto;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\Exception\TemplateNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Service\CloneTemplateService\Exception\CloneTemplateServiceException;
use Skyeng\MarketingCmsBundle\UI\Controller\Admin\Template\CloneTemplate\Validation\CloneTemplateForm;
use Skyeng\MarketingCmsBundle\UI\Controller\Admin\TemplateCrudController;
use Skyeng\MarketingCmsBundle\UI\Service\Response\ResponseFactory;
use Skyeng\MarketingCmsBundle\UI\Service\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CloneTemplateController extends AbstractController
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var CloneTemplateService
     */
    private $cloneTemplateService;

    /**
     * @var AdminUrlGenerator
     */
    private $adminUrlGenerator;

    public function __construct(
        CloneTemplateService $cloneTemplateService,
        ValidatorInterface $validator,
        AdminUrlGenerator $adminUrlGenerator
    ) {
        $this->validator = $validator;
        $this->cloneTemplateService = $cloneTemplateService;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    /**
     * Склонировать готовый компонент
     *
     * @Route("/admin/template/clone", name="clone_template", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $dto = new CloneTemplateRequestDto();

        try {
            $queryParams = $request->query->all()['routeParams'] ?? [];
            $this->validator->validate(CloneTemplateForm::class, $queryParams, $dto);
        } catch (ValidationException $exception) {
            return ResponseFactory::createErrorResponse($request, $exception->getMessage(), $exception->getErrors());
        }

        try {
            $result = $this->cloneTemplateService->cloneTemplate($dto);
            $this->addFlash('success', 'Готовый компонент успешно склонирован');
            return ResponseFactory::createRedirectResponse($result->url);
        } catch (TemplateNotFoundException $exception) {
            return ResponseFactory::createErrorResponse($request, 'Готовый компонент не найден', [], Response::HTTP_NOT_FOUND);
        } catch (CloneTemplateServiceException $e) {
            $url = $this->adminUrlGenerator
                ->setController(TemplateCrudController::class)
                ->setAction('index')
                ->generateUrl();
            $this->addFlash('error', 'Произошла ошибка во время клонирования готового компонента');
            return ResponseFactory::createRedirectResponse($url);
        }
    }
}
