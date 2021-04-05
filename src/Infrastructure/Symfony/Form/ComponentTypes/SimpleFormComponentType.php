<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\ComponentTypes;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SimpleFormComponentType extends AbstractType implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('field', TextType::class)
            ->add('stk', IntegerType::class);

        $builder->resetViewTransformers();
        $builder->addViewTransformer($this);
    }

    public function transform($value): array
    {
        $field = $value['field'] ?? null;
        $stk = $value['stk'] ?? null;

        return [
            'field' => (string)$field,
            'stk' => (int)$stk,
        ];
    }

    public function reverseTransform($value): array
    {
        return $value;
    }
}
