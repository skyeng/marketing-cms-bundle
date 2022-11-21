<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\UrlService;

interface CurrentUrlProviderServiceInterface
{
    public function getUri(): string;

    public function getSchemeAndHost(): string;
}
