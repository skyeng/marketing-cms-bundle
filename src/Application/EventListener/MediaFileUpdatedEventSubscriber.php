<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\EventListener;

use Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Service\MediaFileTypeResolver;
use Skyeng\MarketingCmsBundle\Domain\Entity\MediaFile;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileStorage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Mapping\PropertyMappingFactory;

class MediaFileUpdatedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private MediaFileTypeResolver $mediaFileTypeResolver,
        private PropertyMappingFactory $fileMappingFactory
    ) {
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

        if ($object->getFile() === null) {
            return;
        }

        $object->setType($this->mediaFileTypeResolver->getMediaFileTypeByFile($object->getFile()));
        $mapping = $this->fileMappingFactory->fromField($object, 'file');

        if ($mapping !== null) {
            $object->setStorage(new MediaFileStorage($mapping->getUploadDestination()));
        }
    }

    /**
     * @return array<string, mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return ['vich_uploader.pre_upload' => 'onVichUploaderPreUpdate'];
    }
}
