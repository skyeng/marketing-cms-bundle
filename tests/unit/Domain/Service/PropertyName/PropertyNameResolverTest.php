<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Unit\Domain\Service\PropertyName;

use Codeception\Test\Unit;
use Skyeng\MarketingCmsBundle\Domain\Service\Locale\LocaleResolverInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\PropertyName\PropertyNameResolver;
use Skyeng\MarketingCmsBundle\Tests\Builder\FieldConfigBuilder;

class PropertyNameResolverTest extends Unit
{
    public function testCorrectNameWithLocalizedFieldAndLocale(): void
    {
        /** @var LocaleResolverInterface $localeResolver */
        $localeResolver = $this->makeEmpty(LocaleResolverInterface::class, [
            'getLocaleForField' => 'ru',
        ]);

        $fieldConfig = FieldConfigBuilder::fieldConfig()->build();

        $propertyNameResolver = new PropertyNameResolver(
            $localeResolver
        );

        $this->assertSame(
            'Text-ru-title',
            $propertyNameResolver->getPropertyNameValue($fieldConfig)
        );
    }

    public function testCorrectNameWithoutLocale(): void
    {
        /** @var LocaleResolverInterface $localeResolver */
        $localeResolver = $this->makeEmpty(LocaleResolverInterface::class, [
            'getLocaleForField' => '',
        ]);

        $fieldConfig = FieldConfigBuilder::fieldConfig()->build();

        $propertyNameResolver = new PropertyNameResolver(
            $localeResolver
        );

        $this->assertSame(
            'Text--title',
            $propertyNameResolver->getPropertyNameValue($fieldConfig)
        );
    }
}
