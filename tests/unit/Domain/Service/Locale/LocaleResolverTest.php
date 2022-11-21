<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Unit\Domain\Service\Locale;

use Codeception\Test\Unit;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Locale\LocaleConfigurationInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\RequestStack\Locale\LocaleResolver;
use Skyeng\MarketingCmsBundle\Tests\Builder\FieldConfigBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class LocaleResolverTest extends Unit
{
    public function testGetLocaleForLocalizedField(): void
    {
        /** @var RequestStack $requestStack */
        $requestStack = $this->makeEmpty(RequestStack::class, [
            'getCurrentRequest' => $this->makeEmpty(Request::class, [
                'get' => 'ru',
            ]),
        ]);

        /** @var LocaleConfigurationInterface $localeConfiguration */
        $localeConfiguration = $this->makeEmpty(LocaleConfigurationInterface::class, [
            'getDefaultLocale' => 'ru',
        ]);

        $fieldConfig = FieldConfigBuilder::fieldConfig()->build();

        $localeResolver = new LocaleResolver($requestStack, $localeConfiguration);

        $this->assertSame(
            'ru',
            $localeResolver->getLocaleForField($fieldConfig),
        );
    }

    public function testGetLocaleForNotLocalizedField(): void
    {
        /** @var RequestStack $requestStack */
        $requestStack = $this->makeEmpty(RequestStack::class, [
            'getCurrentRequest' => $this->makeEmpty(Request::class, [
                'get' => 'ru',
            ]),
        ]);

        /** @var LocaleConfigurationInterface $localeConfiguration */
        $localeConfiguration = $this->makeEmpty(LocaleConfigurationInterface::class, [
            'getDefaultLocale' => 'ru',
        ]);

        $fieldConfig = FieldConfigBuilder::fieldConfig()->withLocalized(false)->build();

        $localeResolver = new LocaleResolver($requestStack, $localeConfiguration);

        $this->assertSame(
            '',
            $localeResolver->getLocaleForField($fieldConfig),
        );
    }
}
