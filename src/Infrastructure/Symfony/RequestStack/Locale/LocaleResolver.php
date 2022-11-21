<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\RequestStack\Locale;

use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Locale\LocaleConfigurationInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\FieldConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\Locale\LocaleResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class LocaleResolver implements LocaleResolverInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private LocaleConfigurationInterface $localeConfiguration
    ) {
    }

    public function getLocaleForField(FieldConfig $fieldConfig): string
    {
        if (!$fieldConfig->isLocalized()) {
            return '';
        }

        return $this->getCurrentLocale();
    }

    public function getCurrentLocale(): string
    {
        if (!$this->requestStack->getCurrentRequest() instanceof Request) {
            return '';
        }

        return $this->requestStack->getCurrentRequest()->get('locale', $this->localeConfiguration->getDefaultLocale());
    }
}
