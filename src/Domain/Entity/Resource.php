<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ResourceType;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Uri;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class Resource
{
    /**
     * @var Id
     */
    private $id;

    /**
     * @var Uri
     */
    private $uri;

    /**
     * @var ResourceType
     */
    private $type;

    public function __construct(Id $id, Uri $uri, ResourceType $type)
    {
        $this->id = $id;
        $this->uri = $uri;
        $this->type = $type;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getUri(): Uri
    {
        return $this->uri;
    }

    public function getType(): ResourceType
    {
        return $this->type;
    }

    public function setUri(Uri $uri): void
    {
        $this->uri = $uri;
    }
}
