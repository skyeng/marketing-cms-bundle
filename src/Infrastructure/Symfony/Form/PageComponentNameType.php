<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\PageComponentName;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class PageComponentNameType extends ChoiceType implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder->resetViewTransformers();
        $builder->addViewTransformer($this);
    }

    public function transform($choice): string
    {
        return (string) $choice;
    }

    public function reverseTransform($value): PageComponentName
    {
        if (!is_string($value)) {
            throw new TransformationFailedException('Expected a string.');
        }

        if (!in_array($value, PageComponentName::AVAILABLE_COMPONENTS)) {
            throw new TransformationFailedException(sprintf('The component "%s" does not exist.', $value));
        }

        return new PageComponentName($value);
    }
}
