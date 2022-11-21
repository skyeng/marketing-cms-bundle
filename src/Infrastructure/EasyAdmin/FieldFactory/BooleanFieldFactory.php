<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\EasyAdmin\FieldFactory;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Skyeng\MarketingCmsBundle\Domain\Entity\FieldType;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\FieldConfig;

class BooleanFieldFactory implements EasyAdminFieldFactoryInterface
{
    use FieldFactoryTrait;

    public function supportedType(): string
    {
        return FieldType::BOOLEAN;
    }

    public function create(FieldConfig $fieldConfig): FieldInterface
    {
        return BooleanField::new($this->getName($fieldConfig), $this->getLabel($fieldConfig))
            ->renderAsSwitch(false);
    }
}
