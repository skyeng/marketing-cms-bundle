<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\EasyAdmin\FieldFactory;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Skyeng\MarketingCmsBundle\Domain\Entity\FieldType;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\FieldConfig;

class TextFieldFactory implements EasyAdminFieldFactoryInterface
{
    use FieldFactoryTrait;

    public function supportedType(): string
    {
        return FieldType::TEXT;
    }

    public function create(FieldConfig $fieldConfig): FieldInterface
    {
        return TextField::new($this->getName($fieldConfig), $this->getLabel($fieldConfig));
    }
}
