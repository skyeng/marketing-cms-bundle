<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Model\Assembler;

use Skyeng\MarketingCmsBundle\Domain\Entity\Field;

final class DefaultFieldValueGetter implements FieldValueGetterInterface
{
    /**
     * {@inheritDoc}
     */
    public function getFieldValue(Field $field, ?string $locale = null)
    {
        return $field->getValue();
    }
}
