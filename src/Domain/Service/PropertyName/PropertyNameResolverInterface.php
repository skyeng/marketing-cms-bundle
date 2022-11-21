<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\PropertyName;

use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\FieldConfig;

interface PropertyNameResolverInterface
{
    public function getPropertyNameValue(FieldConfig $fieldConfig): string;
}
