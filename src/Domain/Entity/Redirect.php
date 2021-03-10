<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use Skyeng\MarketingCmsBundle\Domain\Entity\Resource as ResourceEntity;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class Redirect
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
     * @var string
     */
    private $targetUrl;

    /**
     * @var int
     */
    private $httpCode;

    public function __construct(
        Id $id,
        ResourceEntity $resource,
        string $targetUrl,
        int $httpCode
    ) {
        $this->id = $id;
        $this->resource = $resource;
        $this->targetUrl = $targetUrl;
        $this->httpCode = $httpCode;
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
}
