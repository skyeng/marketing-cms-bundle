<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Builder;

use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\TemplateComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\PageComponentName;

class TemplateComponentBuilder
{
    /**
     * @var Id
     */
    private $id;

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

    /**
     * @var bool
     */
    private $published;

    public function __construct()
    {
        $this->id = new Id(Uuid::uuid4()->toString());
        $this->name = new PageComponentName('html-component');
        $this->data = [];
        $this->order = 1;
        $this->published = true;
    }

    public static function templateComponent(): self
    {
        return new self();
    }

    public function build(): TemplateComponent
    {
        $template = new TemplateComponent(
            $this->id,
            null,
            $this->name,
            $this->data,
            $this->order
        );

        $template->setIsPublished($this->published);

        return $template;
    }

    public function withOrder(int $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function withData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function withName(PageComponentName $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function withPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }
}