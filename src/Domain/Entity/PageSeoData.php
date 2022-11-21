<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

class PageSeoData
{
    /**
     * @var Page
     */
    private $page;

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string|null
     */
    private $keywords;

    /**
     * @var boolean
     */
    private $isNoIndex;

    /**
     * @var boolean
     */
    private $isNoFollow;

    public function __construct(
        Page $page,
        ?string $title,
        ?string $description,
        ?string $keywords,
        bool $isNoFollow,
        bool $isNoIndex
    ) {
        $this->page = $page;
        $this->title = $title;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->isNoFollow = $isNoFollow;
        $this->isNoIndex = $isNoIndex;
    }

    public function getPage(): Page
    {
        return $this->page;
    }

    public function setPage(Page $page): void
    {
        $this->page = $page;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): void
    {
        $this->keywords = $keywords;
    }

    public function isNoIndex(): bool
    {
        return $this->isNoIndex;
    }

    public function setIsNoIndex(bool $isNoIndex): void
    {
        $this->isNoIndex = $isNoIndex;
    }

    public function isNoFollow(): bool
    {
        return $this->isNoFollow;
    }

    public function setIsNoFollow(bool $isNoFollow): void
    {
        $this->isNoFollow = $isNoFollow;
    }
}
