<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\CacheTime;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class CacheTimeType extends ChoiceType implements DataTransformerInterface
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

    public function reverseTransform(mixed $value): CacheTime
    {
        if (!is_string($value)) {
            throw new TransformationFailedException('Expected a string.');
        }

        return new CacheTime($value);
    }
}
