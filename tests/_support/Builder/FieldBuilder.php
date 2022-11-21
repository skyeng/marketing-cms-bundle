<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Builder;

use Skyeng\MarketingCmsBundle\Domain\Entity\Field;
use Skyeng\MarketingCmsBundle\Domain\Entity\FieldType;
use Skyeng\MarketingCmsBundle\Domain\Entity\Model;

class FieldBuilder
{
    private Model $model;

    private string $name = 'name';

    private mixed $value = '';

    private string $type = FieldType::TEXT;

    private const LOCALE = 'ru';

    public function __construct()
    {
        $this->model = ModelBuilder::model()->build();
    }

    public static function field(): self
    {
        return new self();
    }

    public function build(): Field
    {
        return new Field(
            $this->model,
            $this->name,
            $this->value,
            $this->type,
            self::LOCALE
        );
    }

    public function withComponents(array $components): self
    {
        $this->value = $components;

        return $this;
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function withValue(mixed $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function withType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function withModel(Model $model): self
    {
        $this->model = $model;

        return $this;
    }
}
