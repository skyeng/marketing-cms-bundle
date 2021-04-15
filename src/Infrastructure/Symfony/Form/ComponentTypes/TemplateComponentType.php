<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\ComponentTypes;

use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\ComponentType\ComponentTypeInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\TemplateRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class TemplateComponentType extends AbstractType implements DataTransformerInterface, ComponentTypeInterface
{
    private const NAME = 'template-component';
    private const TITLE = 'Шаблон';

    /**
     * @var TemplateRepositoryInterface
     */
    private $templateRepository;

    public function __construct(TemplateRepositoryInterface $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $templateChoices = [];

        foreach ($this->templateRepository->getAll() as $template) {
            $templateChoices[$template->getName()] = $template->getId()->getValue();
        }

        $builder->add('template', ChoiceType::class, [
            'choices' => $templateChoices,
            'required' => true,
            'label' => 'Компонент',
            'placeholder' => '',
            'attr' => [
                'styles' => 'height: auto;',
                'rows' => 10,
                'class' => 'field-select',
                'data-widget' => 'select2',
                'data-placeholder' => 'Выберите компонент',
                'data-select' => 'true',
            ]
        ]);

        $builder->resetViewTransformers();
        $builder->addViewTransformer($this);
    }

    public function transform($value): array
    {
        $value = $value['template'] ?? null;

        return ['template' => (string)$value];
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
