<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ComponentName;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class Component extends AbstractComponent
{
    use PublishedTrait;

    /**
     * @param mixed[] $data
     */
    public function __construct(
        private Id $id,
        private Field $field,
        private ComponentName $name,
        /** @var mixed[] */
        private array $data,
        private int $order
    ) {
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getField(): Field
    {
        return $this->field;
    }

    public function setField(Field $field): void
    {
        $this->field = $field;
    }

    public function getName(): ComponentName
    {
        return $this->name;
    }

    public function setName(ComponentName $name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param mixed[] $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function setOrder(int $order): void
    {
        $this->order = $order;
    }
}
