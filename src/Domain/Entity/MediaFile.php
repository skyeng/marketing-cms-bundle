<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileStorage;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileType;

class MediaFile
{
    /**
     * @var Id
     */
    private $id;

    /**
     * @var MediaCatalog
     */
    private $catalog;

    /**
     * @var string
     */
    private $title;

    /**
     * @var MediaFileType
     */
    private $type;

    /**
     * @var string
     */
    private $name;

    /**
     * @var MediaFileStorage
     */
    private $storage;

    public function __construct(Id $id, MediaCatalog $catalog, string $title, MediaFileType $type, string $name, MediaFileStorage $storage)
    {
        $this->id = $id;
        $this->catalog = $catalog;
        $this->title = $title;
        $this->type = $type;
        $this->name = $name;
        $this->storage = $storage;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getCatalog(): MediaCatalog
    {
        return $this->catalog;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getType(): MediaFileType
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStorage(): MediaFileStorage
    {
        return $this->storage;
    }
}
