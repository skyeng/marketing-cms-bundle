<?php

declare(strict_types=1);

namespace _CG_APPROOT_\Infrastructure\Symfony\Form\ComponentTypes;

use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\ComponentType\ComponentTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;

class _CG_FORMATTED_NAME_ComponentType extends AbstractType implements DataTransformerInterface, ComponentTypeInterface
{
    private const NAME = '_CG_NAME_';
    private const TITLE = '_CG_TITLE_';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//        $builder
//            ->add('field', TextType::class, [
//                'label' => 'Field',
//            ]);

        $builder->resetViewTransformers();
        $builder->addViewTransformer($this);
    }

    public function transform($value): array
    {
//        $field = $value['field'] ?? null;

        return [
//            'field' => (string)$field,
        ];
    }

    public function reverseTransform($value): array
    {
        return $value;
    }

    public function getTitle(): string
    {
        return self::TITLE;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
