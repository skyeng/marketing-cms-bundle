<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\RequestStack;

use Skyeng\MarketingCmsBundle\Domain\Service\UrlService\CurrentUrlProviderServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class CurrentUrlProviderService implements CurrentUrlProviderServiceInterface
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public function getUri(): string
    {
        if (!$this->requestStack->getCurrentRequest() instanceof Request) {
            return '';
        }

        return $this->requestStack->getCurrentRequest()->getUri();
    }

    public function getSchemeAndHost(): string
    {
        if (!$this->requestStack->getCurrentRequest() instanceof Request) {
            return '';
        }

        return $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();
    }
}
