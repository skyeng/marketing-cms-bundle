<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\PageComponentName;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Service\ComponentTypeResolver\ComponentTypeResolverInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Service\ComponentTypeResolver\Exception\ComponentTypeNotFoundException;
use Symfony\Component\Form\ChoiceList\Factory\ChoiceListFactoryInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class PageComponentNameType extends ChoiceType implements DataTransformerInterface
{
    /**
     * @var ComponentTypeResolverInterface
     */
    private $componentTypeResolver;

    public function __construct(
        ComponentTypeResolverInterface $componentTypeResolver,
        ChoiceListFactoryInterface $choiceListFactory = null,
        $translator = null
    ) {
        parent::__construct($choiceListFactory, $translator);
        $this->componentTypeResolver = $componentTypeResolver;
    }

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

        try {
            $this->componentTypeResolver->resolveByName($value);
        } catch (ComponentTypeNotFoundException $e) {
            throw new TransformationFailedException(sprintf('The component "%s" does not exist.', $value), 0, $e);
        }

        return new PageComponentName($value);
    }
}
