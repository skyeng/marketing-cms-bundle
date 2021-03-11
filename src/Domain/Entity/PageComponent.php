<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\PageComponentName;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class PageComponent
{
    use PublishedTrait;

    /**
     * @var Id
     */
    private $id;

    /**
     * @var Page
     */
    private $page;

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

    public function __construct(Id $id, ?Page $page, PageComponentName $name, array $data, int $order)
    {
        $this->id = $id;
        $this->page = $page;
        $this->name = $name;
        $this->data = $data;
        $this->order = $order;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getPage(): Page
    {
        return $this->page;
    }

    public function setPage(Page $page): void
    {
        $this->page = $page;
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
