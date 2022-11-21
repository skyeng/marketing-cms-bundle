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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Skyeng\MarketingCmsBundle\Domain\Entity\Redirect;
use Skyeng\MarketingCmsBundle\Domain\Entity\Resource;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ResourceType;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Uri;
use Skyeng\MarketingCmsBundle\Domain\Repository\RedirectRepository\RedirectRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\ResourceRepository\ResourceRepositoryInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\Fields\ResourceField;

class RedirectCrudController extends AbstractCrudController
{
    public function __construct(
        private RedirectRepositoryInterface $redirectRepository,
        private ResourceRepositoryInterface $resourceRepository
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Redirect::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setPageTitle(Crud::PAGE_INDEX, 'Редирект')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Редирект')
            ->setPageTitle(Crud::PAGE_NEW, 'Создать редирект')
            ->setPageTitle(Crud::PAGE_EDIT, 'Редирект')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPaginatorPageSize(30);
    }

    public function configureFields(string $pageName): iterable
    {
        $resource = ResourceField::new('resource.uri', 'Откуда')->setRequired(true);
        $targetUrl = TextField::new('targetUrl', 'Куда')->setRequired(true);
        $httpCode = ChoiceField::new('httpCode', 'HTTP код')
            ->setChoices(['301' => 301, '302' => 302])
            ->setRequired(true);

        if (in_array($pageName, [Crud::PAGE_INDEX, Crud::PAGE_DETAIL], true)) {
            $createdAt = DateTimeField::new('createdAt', 'Дата создания');

            return [$resource, $targetUrl, $httpCode, $createdAt];
        }

        return [$resource, $targetUrl, $httpCode];
    }

    public function createEntity(string $entityFqcn): Redirect
    {
        return new Redirect(
            $this->redirectRepository->getNextIdentity(),
            new Resource(
                $this->resourceRepository->getNextIdentity(),
                new Uri('/some-uri'),
                new ResourceType(ResourceType::REDIRECT_TYPE)
            ),
            '',
            301,
            new DateTimeImmutable(),
        );
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::BATCH_DELETE)
            ->update(Crud::PAGE_INDEX, Action::DELETE, static fn (Action $action): Action => $action->setIcon('fa fa-trash')->setLabel(''));
    }
}
