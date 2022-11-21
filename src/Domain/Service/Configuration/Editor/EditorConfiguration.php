<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Editor;

class EditorConfiguration implements EditorConfigurationInterface
{
    /**
     * @param mixed[] $editorDefinition
     */
    public function __construct(private array $editorDefinition)
    {
    }

    public function getShowEditorLink(): bool
    {
        return $this->editorDefinition['show_editor_link'] ?? false;
    }

    /**
     * {@inheritDoc}
     */
    public function isEnabledSecurity(): bool
    {
        return $this->editorDefinition['security']['enabled'] ?? true;
    }

    /**
     * {@inheritDoc}
     */
    public function getSecurityRoles(): array
    {
        return $this->editorDefinition['security']['roles'] ?? [];
    }
}
