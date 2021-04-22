<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Service\Resolver;

use Skyeng\MarketingCmsBundle\UI\Dto\ComponentFormFieldDto;
use Skyeng\MarketingCmsBundle\UI\Service\Helper\FormFieldTypeHelper;

class ComponentFieldOptionsResolver
{
    public function resolveOptionsByField(ComponentFormFieldDto $componentFormFieldDto): array
    {
        $options = [
            "'label' => '$componentFormFieldDto->name'"
        ];

        if ($componentFormFieldDto->nullable === true) {
            $options[] = "'required' => false";
        }

        if ($componentFormFieldDto->type === FormFieldTypeHelper::TYPE_CHOICE) {
            $options[] = "'choices' => []";
        }

        return $options;
    }
}
