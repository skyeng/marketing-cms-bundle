<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Stringable;

class MediaCatalog implements Stringable
{
    public function __construct(
        private Id $id,
        private string $name
    ) {
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
