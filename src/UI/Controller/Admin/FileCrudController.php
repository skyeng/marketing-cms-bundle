<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Admin;

use DateTimeImmutable;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Skyeng\MarketingCmsBundle\Domain\Entity\File;
use Skyeng\MarketingCmsBundle\Domain\Entity\Resource;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\CacheTime;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ContentType;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ResourceType;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Uri;
use Skyeng\MarketingCmsBundle\Domain\Repository\FileRepository\FileRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\ResourceRepository\ResourceRepositoryInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\CacheTimeType;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\ContentTypeType;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\Fields\ResourceField;

class FileCrudController extends AbstractCrudController
{
    public function __construct(
        private FileRepositoryInterface $fileRepository,
        private ResourceRepositoryInterface $resourceRepository
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return File::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setPageTitle(Crud::PAGE_INDEX, 'Файл')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Файл')
            ->setPageTitle(Crud::PAGE_NEW, 'Создать файл')
            ->setPageTitle(Crud::PAGE_EDIT, 'Файл')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::DELETE, static fn (Action $action): Action => $action->setIcon('fa fa-trash')->setLabel(''))
            ->remove(Crud::PAGE_INDEX, Action::BATCH_DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        $resource = ResourceField::new('resource.uri', 'Ссылка');
        $content = TextareaField::new('content', 'Контент файла');
        $type = ChoiceField::new('contentType', 'Content-type')
            ->setChoices(
                [
                    ContentType::HTML_TYPE => ContentType::HTML_TYPE,
                    ContentType::TEXT_TYPE => ContentType::TEXT_TYPE,
                    ContentType::XML_TYPE => ContentType::XML_TYPE,
                    ContentType::JSON_TYPE => ContentType::JSON_TYPE,
                ]
            )
            ->setFormType(ContentTypeType::class);
        $cacheTime = ChoiceField::new('cacheTime', 'Время кэша')
            ->setChoices(['1ч' => CacheTime::CACHE_TIME_1H, '30мин' => CacheTime::CACHE_TIME_30M])
            ->setFormType(CacheTimeType::class);

        if (in_array($pageName, [Crud::PAGE_INDEX, Crud::PAGE_DETAIL], true)) {
            $createdAt = DateTimeField::new('createdAt', 'Дата создания');

            return [$resource, $type, $cacheTime, $createdAt];
        }

        return [$resource, $content, $type, $cacheTime];
    }

    public function createEntity(string $entityFqcn): File
    {
        return new File(
            $this->fileRepository->getNextIdentity(),
            new Resource(
                $this->resourceRepository->getNextIdentity(),
                new Uri('/file'),
                new ResourceType(ResourceType::FILE_TYPE)
            ),
            new ContentType(ContentType::TEXT_TYPE),
            '',
            new CacheTime(CacheTime::CACHE_TIME_30M),
            new DateTimeImmutable(),
        );
    }
}
