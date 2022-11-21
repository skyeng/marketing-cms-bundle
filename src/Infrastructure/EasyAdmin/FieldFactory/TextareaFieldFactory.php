<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\EasyAdmin\FieldFactory;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Skyeng\MarketingCmsBundle\Domain\Entity\FieldType;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\FieldConfig;

class TextareaFieldFactory implements EasyAdminFieldFactoryInterface
{
    use FieldFactoryTrait;

    public function supportedType(): string
    {
        return FieldType::TEXTAREA;
    }

    public function create(FieldConfig $fieldConfig): FieldInterface
    {
        return TextareaField::new($this->getName($fieldConfig), $this->getLabel($fieldConfig));
    }
}
