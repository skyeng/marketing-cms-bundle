<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class PageCustomMetaTag
{
    /**
     * @var Id
     */
    private $id;

    /**
     * @var Page
     */
    private $page;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $property;

    /**
     * @var string|null
     */
    private $content;

    public function __construct(Id $id, ?Page $page, string $name, ?string $property, ?string $content)
    {
        $this->id = $id;
        $this->page = $page;
        $this->name = $name;
        $this->property = $property;
        $this->content = $content;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(Page $page): void
    {
        $this->page = $page;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getProperty(): ?string
    {
        return $this->property;
    }

    public function setProperty(?string $property): void
    {
        $this->property = $property;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }
}
