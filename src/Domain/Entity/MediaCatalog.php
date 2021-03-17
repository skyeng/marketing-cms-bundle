<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class MediaCatalog
{
    /**
     * @var Id
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    public function __construct(Id $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
