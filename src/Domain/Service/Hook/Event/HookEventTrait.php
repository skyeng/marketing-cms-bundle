<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Hook\Event;

use Skyeng\MarketingCmsBundle\Domain\Entity\Model;

trait HookEventTrait
{
    /**
     * @var Model
     */
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getModel(): Model
    {
        return $this->model;
    }
}
