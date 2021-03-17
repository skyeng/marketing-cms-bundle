<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Skyeng\MarketingCmsBundle\Domain\Entity\MediaFile;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileStorage;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileType;
use Skyeng\MarketingCmsBundle\Domain\Repository\MediaCatalogRepository\MediaCatalogRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\MediaFileRepository\MediaFileRepositoryInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\Fields\VichFileField;

class MediaFileCrudController extends AbstractCrudController
{
    /**
     * @var MediaFileRepositoryInterface
     */
    private $mediaFileRepository;

    /**
     * @var MediaCatalogRepositoryInterface
     */
    private $mediaCatalogRepository;

    public function __construct(
        MediaFileRepositoryInterface $mediaFileRepository,
        MediaCatalogRepositoryInterface $mediaCatalogRepository
    ) {
        $this->mediaFileRepository = $mediaFileRepository;
        $this->mediaCatalogRepository = $mediaCatalogRepository;
    }

    public static function getEntityFqcn(): string
    {
        return MediaFile::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Медиа файлы')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Медиа файл')
            ->setPageTitle(Crud::PAGE_NEW, 'Создать медиа файл')
            ->setPageTitle(Crud::PAGE_EDIT, 'Медиа файл');
    }

    public function configureFields(string $pageName): iterable
    {
        $title = TextField::new('title', 'Заголовок файла');
        $name = TextField::new('name', 'Заголовок файла');
        $file = VichFileField::new('file', 'Файл');
        $catalog = AssociationField::new('catalog', 'Каталог');

        if (in_array($pageName, [Crud::PAGE_INDEX, Crud::PAGE_DETAIL], true)) {
            return [$name, $title, $catalog];
        }

        return [$catalog, $title, $file];
    }

    public function createEntity(string $entityFqcn): MediaFile
    {
        return new MediaFile(
            $this->mediaFileRepository->getNextIdentity(),
            $this->mediaCatalogRepository->getAll()[0],
            '',
            new MediaFileType(MediaFileType::IMAGE_TYPE),
            '',
            new MediaFileStorage(MediaFileStorage::NFS_STORAGE),
        );
    }
}
