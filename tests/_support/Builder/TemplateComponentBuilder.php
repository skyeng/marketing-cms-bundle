<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Builder;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\Domain\Entity\TemplateComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ComponentName;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class TemplateComponentBuilder
{
    private Id $id;

    private ComponentName $name;

    private array $data = [];

    private int $order = 1;

    private bool $published = true;

    private Template $template;

    public function __construct()
    {
        $this->id = new Id(Uuid::uuid4()->toString());
        $this->name = new ComponentName('html-component');
        $this->template = new Template(new Id(Uuid::uuid4()->toString()), 'test-template', new DateTimeImmutable());
    }

    public static function templateComponent(): self
    {
        return new self();
    }

    public function build(): TemplateComponent
    {
        $template = new TemplateComponent(
            $this->id,
            $this->template,
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

    public function withTemplate(Template $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @param mixed[] $data
     */
    public function withData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function withName(ComponentName $name): self
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
