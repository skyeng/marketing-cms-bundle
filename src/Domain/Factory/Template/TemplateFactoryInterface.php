<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Factory\Template;

use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\Domain\Factory\Template\Exception\TemplateCannotBeClonedException;

interface TemplateFactoryInterface
{
    public function create(string $name): Template;

    /**
     * @throws TemplateCannotBeClonedException
     */
    public function clone(Template $template): Template;
}
