<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Builder;

use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\Component;
use Skyeng\MarketingCmsBundle\Domain\Entity\Field;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ComponentName;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class ComponentBuilder
{
    private Id $id;

    private Field $field;

    private ComponentName $name;

    private array $data = [];

    private int $order = 1;

    private bool $published = true;

    public function __construct()
    {
        $this->id = new Id(Uuid::uuid4()->toString());
        $this->name = new ComponentName('html-component');
        $this->field = FieldBuilder::field()->build();
    }

    public static function component(): self
    {
        return new self();
    }

    public function build(): Component
    {
        $component = new Component(
            $this->id,
            $this->field,
            $this->name,
            $this->data,
            $this->order,
        );

        $component->setIsPublished($this->published);

        return $component;
    }

    public function withOrder(int $order): self
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @param mixed[] $data
     */
    public function withData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function withName(ComponentName $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function withPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function withField(Field $field): self
    {
        $this->field = $field;

        return $this;
    }
}
