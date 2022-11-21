<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Locale;

interface LocaleConfigurationInterface
{
    public function getDefaultLocale(): string;

    /**
     * @return string[]
     */
    public function getLocales(): array;
}
