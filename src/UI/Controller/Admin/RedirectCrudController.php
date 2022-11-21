<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Admin;

use Skyeng\MarketingCmsBundle\Domain\Entity\Redirect;
use Skyeng\MarketingCmsBundle\Domain\Entity\Resource;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ResourceType;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Uri;
use Skyeng\MarketingCmsBundle\Domain\Repository\RedirectRepository\RedirectRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\ResourceRepository\ResourceRepositoryInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\Fields\ResourceField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RedirectCrudController extends AbstractCrudController
{
    /**
     * @var RedirectRepositoryInterface
     */
    private $redirectRepository;

    /**
     * @var ResourceRepositoryInterface
     */
    private $resourceRepository;

    public function __construct(
        RedirectRepositoryInterface $redirectRepository,
        ResourceRepositoryInterface $resourceRepository
    ) {
        $this->redirectRepository = $redirectRepository;
        $this->resourceRepository = $resourceRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Redirect::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Редирект')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Редирект')
            ->setPageTitle(Crud::PAGE_NEW, 'Создать редирект')
            ->setPageTitle(Crud::PAGE_EDIT, 'Редирект');
    }

    public function configureFields(string $pageName): iterable
    {
        $resource = ResourceField::new('resource.uri', 'Откуда')->setRequired(true);
        $targetUrl = TextField::new('targetUrl', 'Куда')->setRequired(true);
        $httpCode = ChoiceField::new('httpCode', 'HTTP код')
            ->setChoices(['301' => 301, '302' => 302])
            ->setRequired(true);

        return [$resource, $targetUrl, $httpCode];
    }

    public function createEntity(string $entityFqcn): Redirect
    {
        return new Redirect(
            $this->resourceRepository->getNextIdentity(),
            new Resource(
                $this->resourceRepository->getNextIdentity(),
                new Uri('/some-uri'),
                new ResourceType(ResourceType::REDIRECT_TYPE)
            ),
            '',
            301
        );
    }
}
