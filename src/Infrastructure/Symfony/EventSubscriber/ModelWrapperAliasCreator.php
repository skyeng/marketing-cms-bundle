<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\EventSubscriber;

use Skyeng\MarketingCmsBundle\UI\Controller\Admin\ModelCrudController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class ModelWrapperAliasCreator implements EventSubscriberInterface
{
    public function __construct(array $models)
    {
        foreach ($models as $model) {
            $class = sprintf('%s.CRUD', $model);

            if (!class_exists($class)) {
                class_alias(ModelCrudController::class, $class);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => [
                ['onKernelRequest', 100],
            ],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
    }
}
