<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\EasyAdmin\EventSubscriber;

use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Event\PreCreateHookEvent;
use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Event\PreUpdateHookEvent;
use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Manager\HookManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    public function __construct(private HookManagerInterface $hookManager)
    {
    }

    /**
     * @return array<class-string, array<string[]>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => [
                ['handleBeforeEntityPersistedEvent'],
            ],
            BeforeEntityUpdatedEvent::class => [
                ['handleBeforeEntityUpdatedEvent'],
            ],
        ];
    }

    public function handleBeforeEntityPersistedEvent(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Model) {
            $this->hookManager->handle(new PreCreateHookEvent($entity));
        }
    }

    public function handleBeforeEntityUpdatedEvent(BeforeEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Model) {
            $this->hookManager->handle(new PreUpdateHookEvent($entity));
        }
    }
}
