<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use DateTimeInterface;
use Skyeng\MarketingCmsBundle\Domain\Entity\Resource as ResourceEntity;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\CacheTime;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ContentType;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class File
{
    public function __construct(
        private Id $id,
        private ResourceEntity $resource,
        private ContentType $contentType,
        private string $content,
        private CacheTime $cacheTime,
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

    public function getContentType(): ContentType
    {
        return $this->contentType;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCacheTime(): CacheTime
    {
        return $this->cacheTime;
    }

    public function setResource(Resource $resource): void
    {
        $this->resource = $resource;
    }

    public function setContentType(ContentType $contentType): void
    {
        $this->contentType = $contentType;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setCacheTime(CacheTime $cacheTime): void
    {
        $this->cacheTime = $cacheTime;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
