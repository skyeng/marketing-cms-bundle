<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Locale;

use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Locale\Exception\IncorrectDefaultLocaleException;

class LocaleConfiguration implements LocaleConfigurationInterface
{
    /**
     * @var string[]
     */
    private array $locales = [];

    private string $defaultLocale;

    /**
     * @param string[] $locales
     */
    public function __construct(
        /** @var string[] */
        array $locales,
        string $defaultLocale
    ) {
        if ($locales !== [] && (empty($defaultLocale) || !in_array($defaultLocale, $locales, true))) {
            throw new IncorrectDefaultLocaleException(sprintf('Incorrect Default Locale value «%s»', $defaultLocale));
        }

        $this->locales = $locales;
        $this->defaultLocale = $defaultLocale;
    }

    public function getDefaultLocale(): string
    {
        return $this->defaultLocale;
    }

    /**
     * @return string[]
     */
    public function getLocales(): array
    {
        return $this->locales;
    }
}
