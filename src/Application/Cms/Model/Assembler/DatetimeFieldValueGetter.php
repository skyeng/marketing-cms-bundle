<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Model\Assembler;

use DateTimeInterface;
use Skyeng\MarketingCmsBundle\Domain\Entity\Field;

final class DatetimeFieldValueGetter implements FieldValueGetterInterface
{
    public function __construct(private FieldValueGetterInterface $next)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getFieldValue(Field $field, ?string $locale = null)
    {
        $value = $field->getValue();

        if (!$value instanceof DateTimeInterface) {
            return $this->next->getFieldValue($field, $locale);
        }

        return $value->format(DateTimeInterface::ISO8601);
    }
}
