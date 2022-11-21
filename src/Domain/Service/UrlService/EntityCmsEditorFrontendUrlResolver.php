<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\UrlService;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Editor\EditorConfigurationInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Locale\LocaleResolverInterface;

final class EntityCmsEditorFrontendUrlResolver implements CmsEditorFrontendUrlResolverInterface
{
    private const PATTERN_URL = '{{host}}/admin/cms-editor/{{entityId}}?locale={{locale}}&referrer={{referrer}}';

    public function __construct(
        private EditorConfigurationInterface $editorConfiguration,
        private CurrentUrlProviderServiceInterface $currentUrlProviderService,
        private LocaleResolverInterface $localeResolver
    ) {
    }

    public function createUrl(Id $id): string
    {
        if (!$this->showEditorLink()) {
            return '';
        }

        return str_replace(
            ['{{host}}', '{{entityId}}', '{{referrer}}', '{{locale}}'],
            [
                $this->currentUrlProviderService->getSchemeAndHost(),
                $id->getValue(),
                urlencode($this->currentUrlProviderService->getUri()),
                $this->localeResolver->getCurrentLocale(),
            ],
            self::PATTERN_URL,
        );
    }

    public function showEditorLink(): bool
    {
        return $this->editorConfiguration->getShowEditorLink();
    }
}
