<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\EasyAdmin\FieldFactory;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Skyeng\MarketingCmsBundle\Domain\Entity\FieldType;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\FieldConfig;

class ChoiceFieldFactory implements EasyAdminFieldFactoryInterface
{
    use FieldFactoryTrait;

    public function supportedType(): string
    {
        return FieldType::CHOICE;
    }

    public function create(FieldConfig $fieldConfig): FieldInterface
    {
        return ChoiceField::new($this->getName($fieldConfig), $this->getLabel($fieldConfig))
            ->setChoices(static function () use ($fieldConfig): array {
                $result = [];

                foreach ($fieldConfig->getOptionsConfig()->getChoices() as $choice) {
                    $result[$choice->getLabel()] = $choice->getValue();
                }

                return $result;
            });
    }
}
