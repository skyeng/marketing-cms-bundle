<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Admin;

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
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class FileCrudController extends AbstractCrudController
{
    /**
     * @var FileRepositoryInterface
     */
    private $fileRepository;

    /**
     * @var ResourceRepositoryInterface
     */
    private $resourceRepository;

    public function __construct(
        FileRepositoryInterface $fileRepository,
        ResourceRepositoryInterface $resourceRepository
    )
    {
        $this->fileRepository = $fileRepository;
        $this->resourceRepository = $resourceRepository;
    }

    public static function getEntityFqcn(): string
    {
        return File::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Файл')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Файл')
            ->setPageTitle(Crud::PAGE_NEW, 'Создать файл')
            ->setPageTitle(Crud::PAGE_EDIT, 'Файл');
    }

    public function configureFields(string $pageName): iterable
    {
        $resource = ResourceField::new('resource.uri', 'Ссылка');
        $content = TextareaField::new('content', 'Контент файла');
        $type = ChoiceField::new('contentType', 'Content-type')
            ->setChoices([
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
            return [$resource, $type, $cacheTime];
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
            new CacheTime(CacheTime::CACHE_TIME_30M)
        );
    }
}
