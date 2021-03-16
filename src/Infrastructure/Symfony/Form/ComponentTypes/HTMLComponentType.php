<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\ComponentTypes;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class HTMLComponentType extends AbstractType implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('html', TextareaType::class);

        $builder->resetViewTransformers();
        $builder->addViewTransformer($this);
    }

    public function transform($value): array
    {
        $value = $value['html'] ?? null;

        return ['html' => (string)$value];
    }

    public function reverseTransform($value): array
    {
        return $value;
    }
}
