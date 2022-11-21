<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Builder;

use DateTimeImmutable;
use DateTimeInterface;
use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\Domain\Entity\TemplateComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class TemplateBuilder
{
    private Id $id;

    private const NAME = 'template';

    private const PUBLISHED = true;

    /**
     * @var TemplateComponent[]
     */
    private array $components = [];

    private DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->id = new Id(Uuid::uuid4()->toString());
        $this->createdAt = new DateTimeImmutable('now');
    }

    public static function template(): self
    {
        return new self();
    }

    public function build(): Template
    {
        $template = new Template(
            $this->id,
            self::NAME,
            $this->createdAt,
        );

        $template->setIsPublished(self::PUBLISHED);

        foreach ($this->components as $component) {
            $template->addComponent($component);
        }

        return $template;
    }

    /**
     * @param TemplateComponent[] $components
     */
    public function withComponents(array $components): self
    {
        $this->components = $components;

        return $this;
    }
}
