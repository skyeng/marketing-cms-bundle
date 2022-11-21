<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ResourceType;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Uri;

class Resource
{
    public function __construct(
        private Id $id,
        private Uri $uri,
        private ResourceType $type
    ) {
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
