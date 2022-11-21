<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\ComponentTypes;

use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\ComponentType\ComponentPreviewInterface;
use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\ComponentType\ComponentTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class HTMLComponentType extends AbstractType implements DataTransformerInterface, ComponentTypeInterface, ComponentPreviewInterface
{
    private const NAME = 'html-component';
    private const TITLE = 'HTML';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('html', TextareaType::class, [
            'attr' => [
                'styles' => 'height: auto;',
                'rows' => 10,
            ]
        ]);

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

    public function getTitle(): string
    {
        return self::TITLE;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getPreview(): string
    {
        return 'bundles/marketingcms/component-previews/html-component-preview.png';
    }
}
