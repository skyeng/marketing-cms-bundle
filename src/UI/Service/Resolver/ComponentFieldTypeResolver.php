<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Service\Resolver;

use Skyeng\MarketingCmsBundle\UI\Service\Helper\FormFieldTypeHelper;
use Skyeng\MarketingCmsBundle\UI\Service\Resolver\Exception\IncorrectComponentFieldTypeException;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ComponentFieldTypeResolver
{
    public function resolveClassTypeByType(string $type): ?string
    {
        if (!in_array($type, FormFieldTypeHelper::AVAILABLE_TYPES, true)) {
            throw new IncorrectComponentFieldTypeException(sprintf('Тип %s не поддерживается', $type));
        }

        switch ($type) {
            case FormFieldTypeHelper::TYPE_TEXT:
                return TextType::class;
            case FormFieldTypeHelper::TYPE_TEXTAREA:
                return TextareaType::class;
            case FormFieldTypeHelper::TYPE_INTEGER:
                return IntegerType::class;
            case FormFieldTypeHelper::TYPE_BOOLEAN:
                return CheckboxType::class;
            case FormFieldTypeHelper::TYPE_CHOICE:
                return ChoiceType::class;
        }
    }

    public function resolveFieldTypeByType(string $type): string
    {
        if (!in_array($type, FormFieldTypeHelper::AVAILABLE_TYPES, true)) {
            throw new IncorrectComponentFieldTypeException(sprintf('Тип %s не поддерживается', $type));
        }

        switch ($type) {
            case FormFieldTypeHelper::TYPE_BOOLEAN:
                return 'bool';
            case FormFieldTypeHelper::TYPE_INTEGER:
                return 'int';
            default:
                return 'string';
        }
    }
}
