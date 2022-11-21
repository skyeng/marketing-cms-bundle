<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Model\Assembler;

use Skyeng\MarketingCmsBundle\Domain\Entity\Model;

interface ModelAssemblerInterface
{
    /**
     * @return mixed
     */
    public function assemble(Model $model, string $locale = null);
}
