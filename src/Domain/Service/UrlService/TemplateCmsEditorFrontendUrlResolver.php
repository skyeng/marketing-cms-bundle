<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\UrlService;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Editor\EditorConfigurationInterface;

final class TemplateCmsEditorFrontendUrlResolver implements TemplateCmsEditorFrontendUrlResolverInterface
{
    private const PATTERN_URL = '{{host}}/admin/cms-editor/template/{{entityId}}?referrer={{referrer}}';

    public function __construct(
        private EditorConfigurationInterface $editorConfiguration,
        private CurrentUrlProviderServiceInterface $currentUrlProviderService
    ) {
    }

    public function createUrl(Id $id): string
    {
        return str_replace(
            ['{{host}}', '{{entityId}}', '{{referrer}}'],
            [
                $this->currentUrlProviderService->getSchemeAndHost(),
                $id->getValue(),
                urlencode($this->currentUrlProviderService->getUri()),
            ],
            self::PATTERN_URL,
        );
    }

    public function showEditorLink(): bool
    {
        return $this->editorConfiguration->getShowEditorLink();
    }
}
