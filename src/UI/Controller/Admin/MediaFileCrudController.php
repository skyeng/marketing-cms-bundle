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
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Service\MediaFilePathResolver;
use Skyeng\MarketingCmsBundle\Domain\Entity\MediaFile;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileType;
use Skyeng\MarketingCmsBundle\Domain\Factory\MediaFile\MediaFileFactoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\MediaCatalogRepository\Exception\MediaCatalogNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\MediaCatalogRepository\MediaCatalogRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\MediaCatalog\MediaCatalogResolverInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\Fields\VichFileField;

class MediaFileCrudController extends AbstractCrudController
{
    /**
     * @var string
     */
    private const HIDE_MEDIA_CATALOG_SELECTOR_CLEAR_BUTTON = '#MediaFile_catalog + .select2 .select2-selection__clear {display:none;}';

    public function __construct(
        private MediaCatalogRepositoryInterface $mediaCatalogRepository,
        private MediaFilePathResolver $mediaFilePathResolver,
        private MediaFileFactoryInterface $mediaFileFactory,
        private MediaCatalogResolverInterface $mediaCatalogResolver
    ) {
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
            ->showEntityActionsInlined()
            ->setPageTitle(Crud::PAGE_INDEX, 'Медиа файлы')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Медиа файл')
            ->setPageTitle(Crud::PAGE_NEW, 'Создать медиа файл')
            ->setPageTitle(Crud::PAGE_EDIT, 'Медиа файл')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addHtmlContentToHead(sprintf('<style>%s</style>', self::HIDE_MEDIA_CATALOG_SELECTOR_CLEAR_BUTTON))
            ->addJsFile('bundles/marketingcms/ea-copy-actions.js');
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, static fn (Action $action): Action => $action->setLabel('Создать медиа файл'))
            ->add(
                Crud::PAGE_INDEX,
                Action::new('getFileLink', 'Ссылка', 'fa fa-copy')
                    ->linkToUrl(fn (MediaFile $file): ?string => $this->mediaFilePathResolver->getFileUrl($file))
                    ->addCssClass('copy-action-ea'),
            )
            ->add(
                Crud::PAGE_INDEX,
                Action::new('getFileHtml', 'Html', 'fa fa-copy')
                    ->linkToUrl(fn (MediaFile $file): ?string => $this->mediaFilePathResolver->getFileHtml($file))
                    ->addCssClass('copy-action-ea'),
            )
            ->update(Crud::PAGE_INDEX, Action::DELETE, static fn (Action $action): Action => $action->setIcon('fa fa-trash')->setLabel(''))
            ->remove(Crud::PAGE_INDEX, Action::BATCH_DELETE);

        try {
            $this->mediaCatalogRepository->getFirst();
        } catch (MediaCatalogNotFoundException) {
            $actions->remove(Crud::PAGE_INDEX, Action::NEW);
        }

        return $actions;
    }

    public function getFileHtml(AdminContext $context): string
    {
        $mediaFile = $context->getEntity()->getInstance();

        return (string) $mediaFile->getName();
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
            $createdAt = DateTimeField::new('createdAt', 'Дата создания');

            return [$originalName, $title, $catalog, $createdAt];
        }

        if ($pageName === Crud::PAGE_NEW) {
            $file->setRequired(true);
        }

        return [$catalog, $title, $file];
    }

    public function createEntity(string $entityFqcn): MediaFile
    {
        return $this->mediaFileFactory->create(
            $this->mediaCatalogResolver->getCatalogForEditor(),
            '',
            null,
        );
    }
}
