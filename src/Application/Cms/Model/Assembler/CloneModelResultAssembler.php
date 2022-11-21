<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Model\Assembler;

use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Skyeng\MarketingCmsBundle\Application\Cms\Model\Dto\CloneModelResultDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\Model;

class CloneModelResultAssembler
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {
    }

    public function assemble(Model $model): CloneModelResultDto
    {
        $result = new CloneModelResultDto();
        $result->url = $this->adminUrlGenerator
            ->setController($model->getName().'.CRUD')
            ->setAction('edit')
            ->setEntityId($model->getId()->getValue())
            ->generateUrl();

        return $result;
    }
}
