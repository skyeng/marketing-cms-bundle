<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ContentType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ContentTypeType extends ChoiceType implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder->resetViewTransformers();
        $builder->addViewTransformer($this);
    }

    public function transform(mixed $value): string
    {
        return (string) $value;
    }

    public function reverseTransform(mixed $value): ContentType
    {
        if (!is_string($value)) {
            throw new TransformationFailedException('Expected a string.');
        }

        if (!in_array($value, ContentType::AVAILABLE_TYPES, true)) {
            throw new TransformationFailedException(sprintf('The choice "%s" does not exist.', $value));
        }

        return new ContentType($value);
    }
}
