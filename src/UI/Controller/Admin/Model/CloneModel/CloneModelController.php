<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Admin\Model\CloneModel;

use Exception;
use Skyeng\MarketingCmsBundle\Application\Cms\Model\Dto\CloneModelRequestDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Model\ModelService;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Skyeng\MarketingCmsBundle\Application\Service\Validator\ValidatorInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Response\ResponseFactory;
use Skyeng\MarketingCmsBundle\UI\Controller\Admin\Model\CloneModel\Validation\CloneModelForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CloneModelController extends AbstractController
{
    public function __construct(
        private ModelService $modelService,
        private ValidatorInterface $validator
    ) {
    }

    /**
     * Клонировать модель.
     */
    #[Route(path: '/admin/model/clone', name: 'clone_model', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $dto = new CloneModelRequestDto();

        try {
            $queryParams = $request->query->all()['routeParams'] ?? [];
            $this->validator->validate(CloneModelForm::class, $queryParams, $dto);

            $result = $this->modelService->cloneModel($dto);
        } catch (ValidationException $e) {
            return ResponseFactory::createErrorResponse($e->getMessage(), $e->getErrors());
        } catch (Exception $e) {
            $this->addFlash('danger', $e->getMessage());

            return ResponseFactory::createRedirectResponse(
                $request->server->get('HTTP_REFERER')
            );
        }
        $this->addFlash('success', 'Модель успешно клонирована');

        return ResponseFactory::createRedirectResponse($result->url);
    }
}
