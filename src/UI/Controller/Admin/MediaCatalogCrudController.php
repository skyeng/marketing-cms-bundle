<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Skyeng\MarketingCmsBundle\Domain\Entity\MediaCatalog;
use Skyeng\MarketingCmsBundle\Domain\Factory\MediaCatalog\MediaCatalogFactoryInterface;

class MediaCatalogCrudController extends AbstractCrudController
{
    public function __construct(private MediaCatalogFactoryInterface $mediaCatalogFactory)
    {
    }

    public static function getEntityFqcn(): string
    {
        return MediaCatalog::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setPageTitle(Crud::PAGE_INDEX, 'Каталог')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Каталог')
            ->setPageTitle(Crud::PAGE_NEW, 'Создать каталог')
            ->setPageTitle(Crud::PAGE_EDIT, 'Каталог')
            ->setDefaultSort(['name' => 'ASC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::DELETE, static fn (Action $action): Action => $action->setIcon('fa fa-trash')->setLabel(''))
            ->remove(Crud::PAGE_INDEX, Action::BATCH_DELETE);
    }

    /**
     * @return TextField[]
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Название'),
        ];
    }

    public function createEntity(string $entityFqcn): MediaCatalog
    {
        return $this->mediaCatalogFactory->create('');
    }
}
