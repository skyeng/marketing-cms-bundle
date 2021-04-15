<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Repository\TemplateComponentRepository;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

interface TemplateComponentRepositoryInterface
{
    public function getNextIdentity(): Id;
}
