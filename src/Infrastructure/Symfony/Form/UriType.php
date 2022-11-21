<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Uri;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UriType extends AbstractType implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->resetViewTransformers();
        $builder->addViewTransformer($this);
    }

    public function transform(mixed $value): string
    {
        return (string) $value;
    }

    public function reverseTransform(mixed $value): Uri
    {
        if (!is_string($value)) {
            throw new TransformationFailedException('Expected a string.');
        }

        if (!preg_match(Uri::PATH_PATTERN, $value)) {
            throw new TransformationFailedException('Uri path must start with / and contain simple characters, -, or.');
        }

        return new Uri($value);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'compound' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'text';
    }
}
