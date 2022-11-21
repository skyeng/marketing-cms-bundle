<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Locale;

use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\FieldConfig;

interface LocaleResolverInterface
{
    public function getLocaleForField(FieldConfig $fieldConfig): string;

    public function getCurrentLocale(): string;
}
