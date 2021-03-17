<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Service\MediaFileTypeResolver;
use Skyeng\MarketingCmsBundle\Domain\Entity\MediaFile;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileStorage;
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

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->handleEventArgs($args);
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->handleEventArgs($args);
    }

    private function handleEventArgs(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();

        if (!$object instanceof MediaFile) {
            return;
        }

        $object->setType($this->mediaFileTypeResolver->getMediaFileTypeByFile($object->getFile()));

        $mapping = $this->fileMappingFactory->fromField($object, 'file');
        $object->setStorage(new MediaFileStorage($mapping->getUploadDestination()));
    }
}
