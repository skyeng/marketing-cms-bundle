<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Model\Assembler;

use Skyeng\MarketingCmsBundle\Domain\Entity\Field;

interface FieldValueGetterInterface
{
    /**
     * @return mixed
     */
    public function getFieldValue(Field $field, ?string $locale = null);
}
