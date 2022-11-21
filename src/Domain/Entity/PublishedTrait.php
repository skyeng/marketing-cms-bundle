<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

trait PublishedTrait
{
    private bool $isPublished = false;

    public function isPublished(): bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): void
    {
        $this->isPublished = $isPublished;
    }
}
