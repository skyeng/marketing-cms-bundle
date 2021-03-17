<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileStorage;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileType;
use Symfony\Component\HttpFoundation\File\File;

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

    /**
     * @var File|null
     */
    private $file;

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

    public function setCatalog(MediaCatalog $catalog): void
    {
        $this->catalog = $catalog;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getType(): MediaFileType
    {
        return $this->type;
    }

    public function setType(MediaFileType $type): void
    {
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getStorage(): MediaFileStorage
    {
        return $this->storage;
    }

    public function setStorage(MediaFileStorage $storage): void
    {
        $this->storage = $storage;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): void
    {
        $this->file = $file;
    }
}
