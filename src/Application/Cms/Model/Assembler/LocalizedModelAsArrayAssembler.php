<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Model\Assembler;

use Skyeng\MarketingCmsBundle\Domain\Entity\Model;

class LocalizedModelAsArrayAssembler implements ModelAssemblerInterface
{
    public function __construct(private FieldValueGetterInterface $fieldValueGetter)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function assemble(Model $model, string $locale = null): array
    {
        $fields = [];

        $fields['id'] = $model->getId()->getValue();

        foreach ($model->getFields() as $field) {
            if ($field->getLocale() === $locale || $field->getLocale() === null) {
                $fields[$field->getName()] = $this->fieldValueGetter->getFieldValue($field, $locale);
            }
        }

        return $fields;
    }
}
