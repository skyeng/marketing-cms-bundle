<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\PageComponentName;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class TemplateComponent extends AbstractComponent
{
    use PublishedTrait;

    /**
     * @var Id
     */
    private $id;

    /**
     * @var Template
     */
    private $template;

    /**
     * @var PageComponentName
     */
    private $name;

    /**
     * @var array
     */
    private $data;

    /**
     * @var int
     */
    private $order;

    public function __construct(Id $id, ?Template $template, PageComponentName $name, array $data, int $order)
    {
        $this->id = $id;
        $this->template = $template;
        $this->name = $name;
        $this->data = $data;
        $this->order = $order;
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

    public function getName(): PageComponentName
    {
        return $this->name;
    }

    public function setName(PageComponentName $name): void
    {
        $this->name = $name;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function setOrder(?int $order): void
    {
        $this->order = $order;
    }
}
