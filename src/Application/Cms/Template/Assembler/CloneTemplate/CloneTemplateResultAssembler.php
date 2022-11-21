<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Template\Assembler\CloneTemplate;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Skyeng\MarketingCmsBundle\Application\Cms\Template\Dto\CloneTemplate\CloneTemplateResultDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\UI\Controller\Admin\TemplateCrudController;

class CloneTemplateResultAssembler
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    public function assemble(Template $template): CloneTemplateResultDto
    {
        return new CloneTemplateResultDto(
            $this->adminUrlGenerator
                ->setController(TemplateCrudController::class)
                ->setAction(Action::EDIT)
                ->setEntityId($template->getId()->getValue())
                ->generateUrl()
        );
    }
}
