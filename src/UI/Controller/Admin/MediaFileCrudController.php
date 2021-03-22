<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Service\MediaFilePathResolver;
use Skyeng\MarketingCmsBundle\Domain\Entity\MediaFile;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileStorage;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileType;
use Skyeng\MarketingCmsBundle\Domain\Repository\MediaCatalogRepository\Exception\MediaCatalogNotFoundException;
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

    /**
     * @var MediaFilePathResolver
     */
    private $mediaFilePathResolver;

    public function __construct(
        MediaFileRepositoryInterface $mediaFileRepository,
        MediaCatalogRepositoryInterface $mediaCatalogRepository,
        MediaFilePathResolver $mediaFilePathResolver
    ) {
        $this->mediaFileRepository = $mediaFileRepository;
        $this->mediaCatalogRepository = $mediaCatalogRepository;
        $this->mediaFilePathResolver = $mediaFilePathResolver;
    }

    public static function getEntityFqcn(): string
    {
        return MediaFile::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(
                ChoiceFilter::new('type', 'Тип файла')->setChoices([MediaFileType::AVAILABLE_TYPES])
            );
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Медиа файлы')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Медиа файл')
            ->setPageTitle(Crud::PAGE_NEW, 'Создать медиа файл')
            ->setPageTitle(Crud::PAGE_EDIT, 'Медиа файл');
    }

    public function configureAssets(Assets $assets): Assets
    {
        $hideMediaCatalogSelectorClearButton = '#MediaFile_catalog + .select2 .select2-selection__clear {display:none;}';

        return $assets
            ->addHtmlContentToHead("<style>$hideMediaCatalogSelectorClearButton</style>")
            ->addJsFile('bundles/marketingcms/ea-copy-actions.js');
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
            return $action->setLabel('Создать медиа файл');
        });

        $getFileLink = Action::new('getFileLink', 'Ссылка', 'fa fa-copy')
            ->linkToUrl(function (MediaFile $file) {
                return $this->mediaFilePathResolver->getFileUrl($file);
            })
            ->addCssClass('copy-action-ea');

        $getFileHtml = Action::new('getFileHtml', 'Html', 'fa fa-copy')
            ->linkToUrl(function (MediaFile $file) {
                return $this->mediaFilePathResolver->getFileHtml($file);
            })
            ->addCssClass('copy-action-ea');

        $actions->add(Crud::PAGE_INDEX, $getFileLink);
        $actions->add(Crud::PAGE_INDEX, $getFileHtml);

        try {
            $this->mediaCatalogRepository->getFirst();
        } catch (MediaCatalogNotFoundException $e) {
            $actions->remove(Crud::PAGE_INDEX, Action::NEW);
        }

        return $actions;
    }

    public function getFileHtml(AdminContext $context)
    {
        $mediaFile = $context->getEntity()->getInstance();

        return $mediaFile->getName();
    }

    public function configureFields(string $pageName): iterable
    {
        $title = TextField::new('title', 'Заголовок файла');
        $file = VichFileField::new('file', 'Файл')->setFormTypeOptions([
            'allow_delete' => false,
        ]);
        $catalog = AssociationField::new('catalog', 'Каталог')->setRequired(true);
        $originalName = TextField::new('originalName', 'Название файла');

        if (in_array($pageName, [Crud::PAGE_INDEX, Crud::PAGE_DETAIL], true)) {
            return [$originalName, $title, $catalog];
        }

        return [$catalog, $title, $file];
    }

    public function createEntity(string $entityFqcn): MediaFile
    {
        return new MediaFile(
            $this->mediaFileRepository->getNextIdentity(),
            $this->mediaCatalogRepository->getFirst(),
            '',
            new MediaFileType(MediaFileType::IMAGE_TYPE),
            '',
            new MediaFileStorage(MediaFileStorage::NFS_STORAGE),
            '',
        );
    }
}
