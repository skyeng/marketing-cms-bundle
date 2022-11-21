<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Builder;

use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\Field;
use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class ModelBuilder
{
    private string $name = 'model';

    /** @var Field[] */
    private array $fields = [];

    public static function model(): self
    {
        return new self();
    }

    public function build(): Model
    {
        $model = new Model(
            new Id(Uuid::uuid4()->toString()),
            $this->name,
        );

        foreach ($this->fields as $field) {
            $model->addField($field);
        }

        return $model;
    }

    /**
     * @param Field[] $fields
     */
    public function withFields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
