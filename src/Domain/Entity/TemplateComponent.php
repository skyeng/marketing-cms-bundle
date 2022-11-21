<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ComponentName;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class TemplateComponent extends AbstractComponent
{
    use PublishedTrait;

    /**
     * @param mixed[] $data
     */
    public function __construct(
        private Id $id,
        private Template $template,
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

    public function getTemplate(): Template
    {
        return $this->template;
    }

    public function setTemplate(Template $template): void
    {
        $this->template = $template;
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
