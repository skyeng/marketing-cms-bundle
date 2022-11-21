<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use DateTimeInterface;
use Skyeng\MarketingCmsBundle\Domain\Entity\Resource as ResourceEntity;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class Redirect
{
    public function __construct(
        private Id $id,
        private ResourceEntity $resource,
        private string $targetUrl,
        private int $httpCode,
        private DateTimeInterface $createdAt
    ) {
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getResource(): ResourceEntity
    {
        return $this->resource;
    }

    public function setResource(ResourceEntity $resource): void
    {
        $this->resource = $resource;
    }

    public function getTargetUrl(): string
    {
        return $this->targetUrl;
    }

    public function setTargetUrl(string $targetUrl): void
    {
        $this->targetUrl = $targetUrl;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function setHttpCode(int $httpCode): void
    {
        $this->httpCode = $httpCode;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
