<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Admin;

use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\TemplateRepositoryInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\TemplateComponentType;

class TemplateCrudController extends AbstractCrudController
{
    /**
     * @var TemplateRepositoryInterface
     */
    private $templateRepository;

    public function __construct(
        TemplateRepositoryInterface $templateRepository
    ) {
        $this->templateRepository = $templateRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Template::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Шаблоны')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Шаблон')
            ->setPageTitle(Crud::PAGE_NEW, 'Создать шаблон')
            ->setPageTitle(Crud::PAGE_EDIT, 'Шаблон')
            ->setPaginatorUseOutputWalkers(true);
    }

    public function configureFields(string $pageName): iterable
    {
        $name = TextField::new('name', 'Название');

        $components = CollectionField::new('components', 'Компоненты')
            ->allowAdd()
            ->allowDelete()
            ->setEntryType(TemplateComponentType::class)
            ->setFormTypeOptions([
                'label' => false,
            ])
            ->addCssClass('expanded-collection')
            ->addCssClass('positionable-collection')
            ->addCssFiles('bundles/marketingcms/expanded-collection-style.css')
            ->addJsFiles('bundles/marketingcms/positionable-collection.js')
            ->addJsFiles('bundles/marketingcms/dynamic-page-components.js');

        if (in_array($pageName, [Crud::PAGE_INDEX, Crud::PAGE_DETAIL], true)) {
            return [$name];
        }

        return [
            $name,
            $components,
        ];
    }

    public function createEntity(string $entityFqcn): Template
    {
        return new Template(
            $this->templateRepository->getNextIdentity(),
            ''
        );
    }
}
