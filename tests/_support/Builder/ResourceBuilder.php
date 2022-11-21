<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Builder;

use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\Resource as ResourceEntity;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ResourceType;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Uri;

class ResourceBuilder
{
    private Id $id;

    private Uri $uri;

    private ResourceType $type;

    private function __construct()
    {
        $this->id = new Id(Uuid::uuid4()->toString());
        $randomPath = Uuid::uuid4()->toString();
        $this->uri = new Uri(sprintf('/%s', $randomPath));
        $this->type = new ResourceType(ResourceType::FILE_TYPE);
    }

    public static function resource(): self
    {
        return new self();
    }

    public function withUri(Uri $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    public function withType(ResourceType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function build(): ResourceEntity
    {
        return new ResourceEntity(
            $this->id,
            $this->uri,
            $this->type
        );
    }
}
