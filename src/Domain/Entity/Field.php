<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class Field
{
    private Id $id;

    private ?string $value = null;

    /**
     * @var Collection|Component[]
     */
    private Collection $components;

    public function __construct(
        private Model $model,
        private string $name,
        mixed $value,
        private string $type,
        private ?string $locale = null
    ) {
        $this->id = new Id(Uuid::uuid4()->toString());
        $this->components = new ArrayCollection();

        $this->setValue($value);
    }

    /**
     * @return Collection|Component[]
     */
    public function getComponents(): Collection
    {
        return $this->components;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        switch ($this->type) {
            case FieldType::BOOLEAN:
                return (bool) $this->value;
            case FieldType::INTEGER:
                return (int) $this->value;
            case FieldType::DATE_TIME:
                return empty($this->value) ? null : new DateTimeImmutable($this->value);
            case FieldType::COMPONENTS:
                return $this->components;
            default:
                return $this->value;
        }
    }

    public function setValue(mixed $value): void
    {
        if ($value instanceof DateTimeInterface) {
            $this->value = $value->format(DateTimeInterface::ATOM);
        } elseif (is_array($value)) {
            $this->setCollection(new ArrayCollection($value));
        } elseif ($value instanceof Collection) {
            $this->setCollection($value);
        } else {
            $this->value = $value === null ? null : (string) $value;
        }
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    private function setCollection(Collection $newCollection): void
    {
        foreach ($this->components as $component) {
            if (!$newCollection->contains($component)) {
                $this->removeComponent($component);
            }
        }

        foreach ($newCollection as $component) {
            if (!$this->components->contains($component)) {
                $this->addComponent($component);
            }
        }
    }

    private function addComponent(Component $component): void
    {
        $component->setField($this);
        $this->components->add($component);
    }

    private function removeComponent(Component $component): void
    {
        $this->components->removeElement($component);
    }
}
