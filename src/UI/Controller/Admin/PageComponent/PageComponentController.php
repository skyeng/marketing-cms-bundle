<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Admin\PageComponent;

use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\PageComponentName;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\PageComponentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageComponentController extends AbstractController
{
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
        $name = $request->get('data-name');
        $component = new PageComponent(
            new Id('1'),
            null,
            new PageComponentName($name),
            [],
            1
        );
        $form = $this->createForm(PageComponentType::class, $component);

        return $this->render('@MarketingCms/empty_form.html.twig', [
            'form' => $form->createView(),
            'themes' => [
                '@EasyAdmin/crud/form_theme.html.twig',
            ],
            'test' => '3123123',
        ]);
    }
}
