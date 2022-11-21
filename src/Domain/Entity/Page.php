<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use Skyeng\MarketingCmsBundle\Domain\Entity\Resource as ResourceEntity;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Page
{
    use PublishedTrait;

    /**
     * @var Id
     */
    private $id;

    /**
     * @var ResourceEntity
     */
    private $resource;

    /**
     * @var string
     */
    private $title;

    /**
     * @var DateTimeInterface
     */
    private $publishedAt;

    /**
     * @var DateTimeInterface
     */
    private $createdAt;

    /**
     * @var DateTimeInterface
     */
    private $updatedAt;

    /**
     * @var PageSeoData
     */
    private $pageSeoData;

    /**
     * @var PageOpenGraphData
     */
    private $pageOpenGraphData;

    /**
     * @var Collection|PageCustomMetaTag[]
     */
    private $customMetaTags;

    /**
     * @var Collection|PageComponent[]
     */
    private $components;

    public function __construct(
        Id $id,
        ResourceEntity $resource,
        string $title,
        ?DateTimeInterface $publishedAt,
        DateTimeInterface $createdAt
    ) {
        $this->resource = $resource;
        $this->title = $title;
        $this->publishedAt = $publishedAt;
        $this->createdAt = $createdAt;
        $this->updatedAt = clone $createdAt;
        $this->id = $id;
        $this->customMetaTags = new ArrayCollection();
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

    public function getResource(): ResourceEntity
    {
        return $this->resource;
    }

    public function setResource(ResourceEntity $resource): void
    {
        $this->resource = $resource;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getPublishedAt(): ?DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?DateTimeInterface $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getPageSeoData(): ?PageSeoData
    {
        return $this->pageSeoData;
    }

    public function setPageSeoData(PageSeoData $pageSeoData): void
    {
        $this->pageSeoData = $pageSeoData;
    }

    public function getPageOpenGraphData(): ?PageOpenGraphData
    {
        return $this->pageOpenGraphData;
    }

    public function setPageOpenGraphData(PageOpenGraphData $pageOpenGraphData): void
    {
        $this->pageOpenGraphData = $pageOpenGraphData;
    }

    /**
     * @return PageCustomMetaTag[]
     */
    public function getCustomMetaTags(): Collection
    {
        return $this->customMetaTags;
    }

    public function addCustomMetaTag(PageCustomMetaTag $customMetaTag): void
    {
        $customMetaTag->setPage($this);
        $this->customMetaTags->add($customMetaTag);
    }

    public function removeCustomMetaTag(PageCustomMetaTag $customMetaTag): void
    {
        $this->customMetaTags->removeElement($customMetaTag);
    }

    /**
     * @return PageComponent[]
     */
    public function getComponents(): Collection
    {
        return $this->components;
    }

    public function addComponent(PageComponent $component): void
    {
        $component->setPage($this);
        $this->components->add($component);
    }

    public function removeComponent(PageComponent $component): void
    {
        $this->components->removeElement($component);
    }
}
