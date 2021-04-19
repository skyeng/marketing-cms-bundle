<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Builder;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\Page;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\Resource;
use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\Domain\Entity\TemplateComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class TemplateBuilder
{
    /**
     * @var Id
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $published;

    /**
     * @var TemplateComponent[]
     */
    private $components;

    public function __construct()
    {
        $this->id = new Id(Uuid::uuid4()->toString());
        $this->name = 'template';
        $this->published = true;
        $this->components = [];
    }

    public static function template(): self
    {
        return new self();
    }

    public function build(): Template
    {
        $template = new Template(
            $this->id,
            $this->name
        );

        $template->setIsPublished($this->published);

        foreach ($this->components as $component) {
            $template->addComponent($component);
        }

        return $template;
    }

    public function withComponents(array $components): self
    {
        $this->components = $components;

        return $this;
    }
}
