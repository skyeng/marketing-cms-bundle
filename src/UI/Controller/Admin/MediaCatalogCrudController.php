<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Skyeng\MarketingCmsBundle\Domain\Entity\MediaCatalog;
use Skyeng\MarketingCmsBundle\Domain\Repository\MediaCatalogRepository\MediaCatalogRepositoryInterface;

class MediaCatalogCrudController extends AbstractCrudController
{
    /**
     * @var MediaCatalogRepositoryInterface
     */
    private $mediaCatalogRepository;

    public function __construct(MediaCatalogRepositoryInterface $mediaCatalogRepository)
    {
        $this->mediaCatalogRepository = $mediaCatalogRepository;
    }

    public static function getEntityFqcn(): string
    {
        return MediaCatalog::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Каталог')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Каталог')
            ->setPageTitle(Crud::PAGE_NEW, 'Создать каталог')
            ->setPageTitle(Crud::PAGE_EDIT, 'Каталог');
    }

    public function configureFields(string $pageName): iterable
    {
        $name = TextField::new('name', 'Название');

        if (in_array($pageName, [Crud::PAGE_INDEX, Crud::PAGE_DETAIL], true)) {
            return [$name];
        }

        return [$name];
    }

    public function createEntity(string $entityFqcn): MediaCatalog
    {
        return new MediaCatalog(
            $this->mediaCatalogRepository->getNextIdentity(),
            '',
        );
    }
}
