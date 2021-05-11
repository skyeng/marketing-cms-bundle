<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Templates\CloneTemplate\Assembler;

use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Skyeng\MarketingCmsBundle\Application\Templates\CloneTemplate\Dto\CloneTemplateResultDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\UI\Controller\Admin\TemplateCrudController;

class CloneTemplateResultAssembler
{
    /**
     * @var AdminUrlGenerator
     */
    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public function assemble(Template $template): CloneTemplateResultDto
    {
        $result = new CloneTemplateResultDto();
        $result->url = $this->adminUrlGenerator
            ->setController(TemplateCrudController::class)
            ->setAction('edit')
            ->setEntityId($template->getId()->getValue())
            ->generateUrl();

        return $result;
    }
}
