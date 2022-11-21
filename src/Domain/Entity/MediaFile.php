<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use DateTimeInterface;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileStorage;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileType;
use Symfony\Component\HttpFoundation\File\File;

class MediaFile
{
    public function __construct(
        private Id $id,
        private MediaCatalog $catalog,
        private string $title,
        private MediaFileType $type,
        private ?string $name,
        private MediaFileStorage $storage,
        private ?string $originalName,
        private DateTimeInterface $createdAt,
        private ?File $file = null
    ) {
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
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

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(?string $originalName): void
    {
        $this->originalName = $originalName;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
