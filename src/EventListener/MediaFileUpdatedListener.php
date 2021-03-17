<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Service\MediaFileTypeResolver;
use Skyeng\MarketingCmsBundle\Domain\Entity\MediaFile;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileStorage;

class MediaFileUpdatedListener
{
    /**
     * @var MediaFileTypeResolver
     */
    private $mediaFileTypeResolver;

    /**
     * @var string
     */
    private $mediaUploadDestination;

    public function __construct(MediaFileTypeResolver $mediaFileTypeResolver, string $mediaUploadDestination)
    {
        $this->mediaFileTypeResolver = $mediaFileTypeResolver;
        $this->mediaUploadDestination = $mediaUploadDestination;
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->handleEventArgs($args);
    }

    public function postPersist(LifecycleEventArgs $args): void
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
        $object->setStorage(new MediaFileStorage($this->mediaUploadDestination));
    }
}
