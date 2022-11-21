<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\PropertyName;

use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\FieldConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\Locale\LocaleResolverInterface;

class PropertyNameResolver implements PropertyNameResolverInterface
{
    public function __construct(private LocaleResolverInterface $localeResolver)
    {
    }

    public function getPropertyNameValue(FieldConfig $fieldConfig): string
    {
        $locale = $this->localeResolver->getLocaleForField($fieldConfig);

        return sprintf('%s-%s-%s', $fieldConfig->getType(), $locale, $fieldConfig->getName());
    }
}
