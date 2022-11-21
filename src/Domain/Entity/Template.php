<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Template
{
    use PublishedTrait;

    /**
     * @var Id
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Collection|TemplateComponent[]
     */
    private $components;

    public function __construct(
        Id $id,
        string $name
    ) {
        $this->id = $id;
        $this->name = $name;
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
}
