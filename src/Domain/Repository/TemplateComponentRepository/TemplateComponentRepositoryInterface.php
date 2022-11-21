<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Repository\TemplateComponentRepository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\Domain\Entity\TemplateComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateComponentRepository\Exception\TemplateComponentRepositoryException;

interface TemplateComponentRepositoryInterface
{
    public function getNextIdentity(): Id;

    public function save(TemplateComponent ...$templateComponents): void;

    public function remove(TemplateComponent ...$templateComponents): void;

    /**
     * @return TemplateComponent[]
     *
     * @throws TemplateComponentRepositoryException
     */
    public function getByTemplate(Template $template): array;
}
