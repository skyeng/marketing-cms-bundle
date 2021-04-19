<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Skyeng\MarketingCmsBundle\Domain\Entity\Page;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageOpenGraphData;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageSeoData;
use Skyeng\MarketingCmsBundle\Domain\Entity\Resource;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ResourceType;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Uri;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageRepository\PageRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\ResourceRepository\ResourceRepositoryInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\Fields\ResourceField;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\PageComponentType;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\PageCustomMetaTagType;
use DateTimeImmutable;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Skyeng\MarketingCmsBundle\UI\Service\Resolver\PageFrontendUrlResolverInterface;

class PageCrudController extends AbstractCrudController
{
    /**
     * @var ResourceRepositoryInterface
     */
    private $resourceRepository;

    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var AdminUrlGenerator
     */
    private $adminUrlGenerator;

    /**
     * @var PageFrontendUrlResolverInterface
     */
    private $pageFrontendUrlResolver;

    public function __construct(
        ResourceRepositoryInterface $resourceRepository,
        PageRepositoryInterface $pageRepository,
        AdminUrlGenerator $adminUrlGenerator
    ) {
        $this->resourceRepository = $resourceRepository;
        $this->pageRepository = $pageRepository;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public function setPageFrontendUrlResolver(?PageFrontendUrlResolverInterface $pageFrontendUrlResolver): void
    {
        $this->pageFrontendUrlResolver = $pageFrontendUrlResolver;
    }

    public static function getEntityFqcn(): string
    {
        return Page::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Страницы')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Страница')
            ->setPageTitle(Crud::PAGE_NEW, 'Создать страницу')
            ->setPageTitle(Crud::PAGE_EDIT, 'Страница')
            ->setPaginatorUseOutputWalkers(true);
    }

    public function configureActions(Actions $actions): Actions
    {
        $mediaFilesAction = Action::new('mediaFiles', 'Медиа библиотека', 'fa fa-image')
            ->linkToUrl(
                $this->adminUrlGenerator
                    ->setController(MediaFileCrudController::class)
                    ->setAction('index')
                    ->generateUrl()
            )
            ->setHtmlAttributes(['target' => '_blank'])
            ->addCssClass('btn')
            ->addCssClass('btn-secondary');

        $actions->add(Crud::PAGE_EDIT, $mediaFilesAction);
        $actions->add(Crud::PAGE_NEW, $mediaFilesAction);

        $pageFrontendUrlResolver = $this->pageFrontendUrlResolver;

        if ($pageFrontendUrlResolver !== null) {
            $actions
                ->add(
                    Crud::PAGE_INDEX,
                    Action::new('pageView', '', 'fa fa-eye')
                        ->linkToUrl(
                            function (Page $page) use ($pageFrontendUrlResolver) {
                                return $pageFrontendUrlResolver->resolve($page);
                            }
                        )
                        ->displayIf(
                            static function (Page $page) {
                                return $page->isPublished();
                            }
                        )
                        ->setHtmlAttributes(['target' => '_blank'])
                );
        }

        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        $resource = ResourceField::new('resource.uri', 'Ссылка');
        $title = TextField::new('title', 'Название');
        $isPublished = BooleanField::new('isPublished', 'Опубликовано');
        $publishedAt = DateTimeField::new('publishedAt', 'Дата публикации')->renderAsChoice();

        $seoDataTitle = TextField::new('pageSeoData.title', 'Meta title');
        $seoDataDescription = TextField::new('pageSeoData.description', 'Meta description');
        $seoDataKeywords = TextField::new('pageSeoData.keywords', 'Meta keywords');
        $seoDataRobotsNoIndex = BooleanField::new('pageSeoData.isNoIndex', 'Meta robots noindex')
            ->renderAsSwitch(false);
        $seoDataRobotsNoFollow = BooleanField::new('pageSeoData.isNoFollow', 'Meta robots nofollow')
            ->renderAsSwitch(false);

        $openGraphType = TextField::new('pageOpenGraphData.type', 'Open graph type');
        $openGraphUrl = TextField::new('pageOpenGraphData.url', 'Open graph url');
        $openGraphTitle = TextField::new('pageOpenGraphData.title', 'Open graph title');
        $openGraphImage = TextField::new('pageOpenGraphData.image', 'Open graph image');
        $openGraphDescription = TextareaField::new('pageOpenGraphData.description', 'Open graph description');

        $customMetaTags = CollectionField::new('customMetaTags', 'Дополнительные мета теги')
            ->allowAdd()
            ->allowDelete()
            ->setEntryType(PageCustomMetaTagType::class);
        $components = CollectionField::new('components', 'Компоненты')
            ->allowAdd()
            ->allowDelete()
            ->setEntryType(PageComponentType::class)
            ->setFormTypeOptions([
                'label' => false,
            ])
            ->addCssClass('expanded-collection')
            ->addCssClass('positionable-collection')
            ->addCssFiles('bundles/marketingcms/expanded-collection-style.css')
            ->addJsFiles('bundles/marketingcms/positionable-collection.js')
            ->addJsFiles('bundles/marketingcms/dynamic-page-components.js');

        if (in_array($pageName, [Crud::PAGE_INDEX, Crud::PAGE_DETAIL], true)) {
            return [$resource, $title, $isPublished, $publishedAt];
        }

        $mainPanel = FormField::addPanel('Основные данные')->collapsible();
        $seoPanel = FormField::addPanel('SEO данные')->collapsible();
        $openGraphPanel = FormField::addPanel('Open Graph данные')->collapsible();
        $customMetaTagsPanel = FormField::addPanel('Дополнительные мета теги')->collapsible();
        $componentsPanel = FormField::addPanel('Компоненты')->collapsible();

        if ($pageName === Crud::PAGE_EDIT) {
            $mainPanel->renderCollapsed();
            $seoPanel->renderCollapsed();
            $openGraphPanel->renderCollapsed();
            $customMetaTagsPanel->renderCollapsed();
        }

        return [
            $mainPanel,
            $resource,
            $title,
            $isPublished,
            $publishedAt,

            $seoPanel,

            $seoDataTitle,
            $seoDataDescription,
            $seoDataKeywords,
            $seoDataRobotsNoIndex,
            $seoDataRobotsNoFollow,

            $openGraphPanel,
            $openGraphType,
            $openGraphUrl,
            $openGraphTitle,
            $openGraphImage,
            $openGraphDescription,

            $customMetaTagsPanel,
            $customMetaTags,

            $componentsPanel,
            $components,
        ];
    }

    public function createEntity(string $entityFqcn): Page
    {
        $page = new Page(
            $this->pageRepository->getNextIdentity(),
            new Resource(
                $this->resourceRepository->getNextIdentity(),
                new Uri('/page'),
                new ResourceType(ResourceType::PAGE_TYPE)
            ),
            '',
            new DateTimeImmutable(),
            new DateTimeImmutable(),
        );

        $page->setPageSeoData(new PageSeoData($page, '', '', '', false, false));
        $page->setPageOpenGraphData(new PageOpenGraphData($page, '', '', '', '', ''));

        return $page;
    }

    public function edit(AdminContext $context)
    {
        $page = $context->getEntity()->getInstance();

        if ($page instanceof Page) {
            if (!$page->getPageSeoData()) {
                $page->setPageSeoData(
                    new PageSeoData($page, '', '', '', false, false)
                );
            }

            if (!$page->getPageOpenGraphData()) {
                $page->setPageOpenGraphData(
                    new PageOpenGraphData($page, '', '', '', '', '')
                );
            }
        }

        return parent::edit($context);
    }
}
