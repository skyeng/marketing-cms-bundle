<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Admin\PageComponentForm;

use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentForm\Dto\GetPageComponentFormRequestDto;
use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentForm\PageComponentFormService;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Skyeng\MarketingCmsBundle\UI\Controller\Admin\PageComponentForm\Validation\GetPageComponentFormV1Form;
use Skyeng\MarketingCmsBundle\UI\Service\Response\ResponseFactory;
use Skyeng\MarketingCmsBundle\UI\Service\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetPageComponentFormController extends AbstractController
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var PageComponentFormService
     */
    private $pageComponentFormService;

    public function __construct(ValidatorInterface $validator, PageComponentFormService $pageComponentFormService)
    {
        $this->validator = $validator;
        $this->pageComponentFormService = $pageComponentFormService;
    }

    /**
     * Получить виджет формы для компонента
     *
     * @Route("/admin/page-component/form", methods={"GET"})
     *
     * @param Request $request
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new GetPageComponentFormRequestDto();

        try {
            $this->validator->validate(GetPageComponentFormV1Form::class, $request->query->all(), $dto);
        } catch (ValidationException $exception) {
            return ResponseFactory::createErrorResponse($request, $exception->getMessage(), $exception->getErrors());
        }

        $result = $this->pageComponentFormService->getPageComponentForm($dto);

        return $this->render('@MarketingCms/page_component/page_component_data_form.html.twig', [
            'form' => $result->result->createView(),
            'themes' => [
                '@EasyAdmin/crud/form_theme.html.twig',
            ],
        ]);
    }
}
