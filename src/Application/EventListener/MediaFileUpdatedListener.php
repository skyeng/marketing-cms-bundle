<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\EventListener;

use Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Service\MediaFileTypeResolver;
use Skyeng\MarketingCmsBundle\Domain\Entity\MediaFile;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileStorage;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Mapping\PropertyMappingFactory;

class MediaFileUpdatedListener
{
    /**
     * @var MediaFileTypeResolver
     */
    private $mediaFileTypeResolver;

    /**
     * @var PropertyMappingFactory
     */
    private $fileMappingFactory;

    public function __construct(MediaFileTypeResolver $mediaFileTypeResolver, PropertyMappingFactory $fileMappingFactory)
    {
        $this->mediaFileTypeResolver = $mediaFileTypeResolver;
        $this->fileMappingFactory = $fileMappingFactory;
    }

    public function onVichUploaderPreUpdate(Event $event): void
    {
        $this->handleEventArgs($event->getObject());
    }

    private function handleEventArgs(object $object): void
    {
        if (!$object instanceof MediaFile) {
            return;
        }

        if (!$object->getFile()) {
            return;
        }

        $object->setType($this->mediaFileTypeResolver->getMediaFileTypeByFile($object->getFile()));
        $mapping = $this->fileMappingFactory->fromField($object, 'file');

        if ($mapping) {
            $object->setStorage(new MediaFileStorage($mapping->getUploadDestination()));
        }
    }
}
