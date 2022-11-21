<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

class PageOpenGraphData
{
    /**
     * @var Page
     */
    private $page;

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var string|null
     */
    private $url;

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $image;

    /**
     * @var string|null
     */
    private $description;

    public function __construct(
        Page $page,
        ?string $type,
        ?string $url,
        ?string $title,
        ?string $image,
        ?string $description
    ) {
        $this->page = $page;
        $this->type = $type;
        $this->url = $url;
        $this->title = $title;
        $this->image = $image;
        $this->description = $description;
    }

    public function getPage(): Page
    {
        return $this->page;
    }

    public function setPage(Page $page): void
    {
        $this->page = $page;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
