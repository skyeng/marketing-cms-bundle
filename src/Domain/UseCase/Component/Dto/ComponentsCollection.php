<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\UseCase\Component\Dto;

use Skyeng\MarketingCmsBundle\Domain\Entity\Component;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

final class ComponentsCollection
{
    /**
     * @var array<string, Component>
     */
    private array $components = [];

    public function __construct(Component ...$components)
    {
        foreach ($components as $component) {
            $this->components[$component->getId()->getValue()] = $component;
        }
    }

    public function findById(Id $componentId): ?Component
    {
        return $this->components[$componentId->getValue()] ?? null;
    }

    public function removeById(Id $componentId): void
    {
        unset($this->components[$componentId->getValue()]);
    }

    public function count(): int
    {
        return count($this->components);
    }

    /**
     * @return Component[]
     */
    public function toArray(): array
    {
        return array_values($this->components);
    }
}
