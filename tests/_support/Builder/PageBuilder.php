<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Builder;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\Page;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\Resource;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class PageBuilder
{
    /**
     * @var Id
     */
    private $id;

    /**
     * @var Resource
     */
    private $resource;

    /**
     * @var string
     */
    private $title;

    /**
     * @var DateTimeImmutable
     */
    private $publishedAt;

    /**
     * @var DateTimeImmutable
     */
    private $createdAt;

    /**
     * @var bool
     */
    private $published;

    /**
     * @var PageComponent[]
     */
    private $components;

    public function __construct()
    {
        $this->id = new Id(Uuid::uuid4()->toString());
        $this->resource = ResourceBuilder::resource()->build();
        $this->title = 'page';
        $this->publishedAt = new DateTimeImmutable('now');
        $this->createdAt = new DateTimeImmutable('now');
        $this->published = true;
        $this->components = [];
    }

    public static function page(): self
    {
        return new self();
    }

    public function build(): Page
    {
        $page = new Page(
            $this->id,
            $this->resource,
            $this->title,
            $this->publishedAt,
            $this->createdAt,
        );

        $page->setIsPublished($this->published);

        foreach ($this->components as $component) {
            $page->addComponent($component);
        }

        return $page;
    }

    public function withComponents(array $components): self
    {
        $this->components = $components;

        return $this;
    }
}
