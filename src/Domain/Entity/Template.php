<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Stringable;

class Template implements Stringable
{
    use PublishedTrait;

    /**
     * @var Collection|TemplateComponent[]
     */
    private Collection $components;

    public function __construct(
        private Id $id,
        private string $name,
        private DateTimeInterface $createdAt
    ) {
        $this->components = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->id->getValue();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection|TemplateComponent[]
     */
    public function getComponents(): Collection
    {
        return $this->components;
    }

    public function addComponent(TemplateComponent $component): void
    {
        $component->setTemplate($this);
        $this->components->add($component);
    }

    public function removeComponent(TemplateComponent $component): void
    {
        $this->components->removeElement($component);
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
