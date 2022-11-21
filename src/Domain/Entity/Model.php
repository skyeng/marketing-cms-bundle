<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\PropertyName;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;

class Model
{
    /**
     * @var Collection|Field[]
     */
    private Collection $fields;

    public function __construct(
        private Id $id,
        private string $name
    ) {
        $this->fields = new ArrayCollection();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Collection|Field[]
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function addField(Field $field): void
    {
        $this->fields->add($field);
    }

    public function __set(string $name, mixed $value): void
    {
        $pn = new PropertyName($name);

        foreach ($this->fields as $field) {
            if ($field->getName() === $pn->propertyName && $field->getLocale() === $pn->locale) {
                $field->setValue($value);

                return;
            }
        }

        $this->addField(new Field($this, $pn->propertyName, $value, $pn->type, $pn->locale));
    }

    /**
     * @return mixed
     */
    public function __get(string $name)
    {
        // костыль чтобы обмануть Form Panels изиАдмина
        if (str_contains($name, 'ea_form_panel')) {
            throw new NoSuchPropertyException();
        }

        $pn = new PropertyName($name);

        foreach ($this->fields as $field) {
            if ($field->getName() === $pn->propertyName && $field->getLocale() === $pn->locale) {
                return $field->getValue();
            }
        }

        return null;
    }
}
