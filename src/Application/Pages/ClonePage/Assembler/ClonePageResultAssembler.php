<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Pages\ClonePage\Assembler;

use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Skyeng\MarketingCmsBundle\Application\Pages\ClonePage\Dto\ClonePageResultDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\Page;
use Skyeng\MarketingCmsBundle\UI\Controller\Admin\PageCrudController;

class ClonePageResultAssembler
{
    /**
     * @var AdminUrlGenerator
     */
    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public function assemble(Page $page): ClonePageResultDto
    {
        $result = new ClonePageResultDto();
        $result->url = $this->adminUrlGenerator
            ->setController(PageCrudController::class)
            ->setAction('edit')
            ->setEntityId($page->getId()->getValue())
            ->generateUrl();

        return $result;
    }
}
