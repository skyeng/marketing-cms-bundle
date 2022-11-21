<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\Fields;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Vich\UploaderBundle\Form\Type\VichFileType;

class VichFileField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setTemplatePath('')
            ->setLabel($label)
            ->setFormType(VichFileType::class);
    }
}
