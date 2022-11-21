<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\Domain\Factory\Template\TemplateFactoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\UrlService\TemplateCmsEditorFrontendUrlResolverInterface;

class TemplateCrudController extends AbstractCrudController
{
    public function __construct(
        private TemplateCmsEditorFrontendUrlResolverInterface $cmsEditorFrontendUrlResolver,
        private TemplateFactoryInterface $templateFactory,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Template::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setPageTitle(Crud::PAGE_INDEX, 'Готовые компоненты')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Готовый компонент')
            ->setPageTitle(Crud::PAGE_NEW, 'Создать готовый компонент')
            ->setPageTitle(Crud::PAGE_EDIT, 'Готовый компонент')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPaginatorUseOutputWalkers(true);
    }

    /**
     * @return TextField[]|DateTimeField[]
     */
    public function configureFields(string $pageName): iterable
    {
        if (in_array($pageName, [Crud::PAGE_INDEX, Crud::PAGE_DETAIL], true)) {
            return [
                TextField::new('name', 'Название'),
                DateTimeField::new('createdAt', 'Дата создания'),
            ];
        }

        return [
            TextField::new('name', 'Название'),
        ];
    }

    public function createEntity(string $entityFqcn): Template
    {
        return $this->templateFactory->create('');
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions
            ->add(
                Crud::PAGE_INDEX,
                Action::new('cloneTemplate', false, 'fa fa-clone')
                    ->linkToRoute(
                        'clone_template',
                        static fn (Template $template): array => [
                            'id' => $template->getId()->getValue(),
                        ]
                    )
                    ->setHtmlAttributes([
                        'onclick' => 'return confirm("Вы действительно хотите склонировать этот готовый компонент?")',
                    ])
            )
            ->remove(Crud::PAGE_INDEX, Action::BATCH_DELETE)
            ->update(Crud::PAGE_INDEX, Action::DELETE, static fn (Action $action): Action => $action->setIcon('fa fa-trash')->setLabel(''));

        if ($this->cmsEditorFrontendUrlResolver->showEditorLink()) {
            $actions->add(
                Crud::PAGE_INDEX,
                Action::new('cmsEditor', 'CMS Editor')
                    ->linkToUrl(
                        fn (Template $template): string => $this->cmsEditorFrontendUrlResolver->createUrl($template->getId())
                    )
                    ->setHtmlAttributes(['target' => '_blank'])
            );
        }

        return parent::configureActions($actions);
    }
}
