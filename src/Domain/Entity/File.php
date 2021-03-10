<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use Skyeng\MarketingCmsBundle\Domain\Entity\Resource as ResourceEntity;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\CacheTime;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ContentType;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class File
{
    /**
     * @var Id
     */
    private $id;

    /**
     * @var ResourceEntity
     */
    private $resource;

    /**
     * @var ContentType
     */
    private $contentType;

    /**
     * @var string
     */
    private $content;

    /**
     * @var CacheTime
     */
    private $cacheTime;

    public function __construct(
        Id $id,
        ResourceEntity $resource,
        ContentType $contentType,
        string $content,
        CacheTime $cacheTime
    ) {
        $this->id = $id;
        $this->resource = $resource;
        $this->contentType = $contentType;
        $this->content = $content;
        $this->cacheTime = $cacheTime;
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
}
