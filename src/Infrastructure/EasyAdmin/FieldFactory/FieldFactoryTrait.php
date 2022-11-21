<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\EasyAdmin\FieldFactory;

use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\FieldConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\Locale\LocaleResolverInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\PropertyName\PropertyNameResolverInterface;

trait FieldFactoryTrait
{
    /**
     * @var PropertyNameResolverInterface
     */
    protected $propertyNameResolver;

    /**
     * @var LocaleResolverInterface
     */
    private $localeResolver;

    public function __construct(
        PropertyNameResolverInterface $propertyNameResolver,
        LocaleResolverInterface $localeResolver
    ) {
        $this->propertyNameResolver = $propertyNameResolver;
        $this->localeResolver = $localeResolver;
    }

    private function getName(FieldConfig $fieldConfig): string
    {
        return $this->propertyNameResolver->getPropertyNameValue($fieldConfig);
    }

    private function getLabel(FieldConfig $fieldConfig): string
    {
        $locale = $this->localeResolver->getLocaleForField($fieldConfig);

        return sprintf('%s %s', $fieldConfig->getLabel(), $locale);
    }
}
