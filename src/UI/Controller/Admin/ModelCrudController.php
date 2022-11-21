<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Admin;

use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Domain\Factory\Model\ModelFactoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Locale\LocaleConfigurationInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\ModelsConfigurationInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Locale\LocaleResolverInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\UrlService\CmsEditorFrontendUrlResolverInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\UrlService\ModelFrontendUrlResolverInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\EasyAdmin\FieldsFactory\FieldsFactory;

class ModelCrudController extends AbstractCrudController
{
    public function __construct(
        private LocaleResolverInterface $localeResolver,
        private LocaleConfigurationInterface $localeConfiguration,
        private ModelsConfigurationInterface $modelsConfiguration,
        private ModelFactoryInterface $modelFactory,
        private FieldsFactory $easyAdminFieldsFactory,
        private ModelFrontendUrlResolverInterface $modelFrontendUrlResolver,
        private CmsEditorFrontendUrlResolverInterface $cmsEditorFrontendUrlResolver,
        private string $modelName = ''
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Model::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setSearchFields(['fields.value']);
    }

    public function configureActions(Actions $actions): Actions
    {
        $switchLocaleActionOnEditPage = $this->createSwitchLocaleAction('switchLocaleActionOnEditPage');
        $switchLocaleActionOnIndexPage = $this->createSwitchLocaleAction('switchLocaleActionOnIndexPage')->createAsGlobalAction();
        $cloneModelActionOnIndexPage = $this->cloneModelAction('cloneModelOnIndexPage');
        $pageViewAction = $this->getPageViewAction();

        $actions
            // Проблема с клиентами у которых несколько дашбордов, AdminUrlGenerator требует указать конкретный дашборд
            //->remove(Crud::PAGE_INDEX, Action::NEW)->add(Crud::PAGE_INDEX, $this->getLocalizedNewAction())
            ->add(Crud::PAGE_EDIT, $switchLocaleActionOnEditPage)
            ->add(Crud::PAGE_INDEX, $switchLocaleActionOnIndexPage)
            ->add(Crud::PAGE_NEW, Action::SAVE_AND_CONTINUE)
            ->update(Crud::PAGE_INDEX, Action::DELETE, static fn (Action $action): Action => $action->setIcon('fa fa-trash')->setLabel(''))
            ->remove(Crud::PAGE_INDEX, Action::BATCH_DELETE);

        $cmsEditorFrontendUrlResolver = $this->cmsEditorFrontendUrlResolver;

        if ($cmsEditorFrontendUrlResolver->showEditorLink()) {
            $actions
                ->add(
                    Crud::PAGE_INDEX,
                    Action::new('cmsEditor', 'CMS Editor')
                        ->linkToUrl(
                            static fn (Model $model): string => $cmsEditorFrontendUrlResolver->createUrl($model->getId())
                        )
                        ->setHtmlAttributes(['target' => '_blank'])
                );
        }

        if ($pageViewAction !== null) {
            $actions->add(Crud::PAGE_INDEX, $pageViewAction);
        }

        if ($cloneModelActionOnIndexPage !== null) {
            $actions->add(Crud::PAGE_INDEX, $cloneModelActionOnIndexPage);
        }

        return $actions;
    }

    private function createSwitchLocaleAction(string $name): Action
    {
        return Action::new($name)
            ->setTemplatePath('@MarketingCms/EasyAdmin/crud/switch_locale_action.twig')
            ->setHtmlAttributes([
                'locales' => $this->localeConfiguration->getLocales(),
                'currentLocale' => $this->localeResolver->getCurrentLocale(),
            ])
            ->linkToUrl('');
    }

    private function cloneModelAction(string $name): ?Action
    {
        if (!$this->modelsConfiguration->getModelConfig($this->modelName)->isCloneable()) {
            return null;
        }

        return Action::new($name, false, 'fa fa-clone')
            ->linkToRoute(
                'clone_model',
                static fn (Model $model): array => [
                    'id' => $model->getId()->getValue(),
                ]
            )
            ->setHtmlAttributes([
                'onclick' => 'return confirm("Вы действительно хотите склонировать эту модель?")',
            ]);
    }

    private function getPageViewAction(): ?Action
    {
        $modelConfig = $this->modelsConfiguration->getModelConfig($this->modelName);

        if (!$this->modelFrontendUrlResolver->canCreateUrl($modelConfig)) {
            return null;
        }

        return Action::new('pageView', '', 'fa fa-eye')
            ->linkToUrl(
                fn (Model $model): string => $this->modelFrontendUrlResolver->createUrl($model)
            )
            ->setHtmlAttributes(['target' => '_blank']);
    }

    /**
     * @return FieldInterface[]
     */
    public function configureFields(string $pageName): iterable
    {
        return $this->easyAdminFieldsFactory->createFields($this->modelName);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $qb->andWhere('entity.name = :name')->setParameter('name', $this->modelName);

        return $qb;
    }

    public function createEntity(string $entityFqcn): Model
    {
        return $this->modelFactory->create($this->modelName);
    }
}
