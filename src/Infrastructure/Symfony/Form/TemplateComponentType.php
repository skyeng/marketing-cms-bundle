<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form;

use Psr\Log\LoggerInterface;
use Skyeng\MarketingCmsBundle\Domain\Entity\TemplateComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\PageComponentName;
use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\Service\ComponentTypeCollection\ComponentTypeCollectionInterface;
use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\Service\ComponentTypeResolver\ComponentTypeResolverInterface;
use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\Service\ComponentTypeResolver\Exception\ComponentTypeNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateComponentRepository\TemplateComponentRepositoryInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\ComponentTypes\TemplateComponentType as TemplateComponentTypeFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateComponentType extends AbstractType
{
    /**
     * @var TemplateComponentRepositoryInterface
     */
    private $componentRepository;

    /**
     * @var ComponentTypeCollectionInterface
     */
    private $componentTypes;

    /**
     * @var ComponentTypeResolverInterface
     */
    private $componentTypeResolver;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        TemplateComponentRepositoryInterface $templateComponentRepository,
        ComponentTypeCollectionInterface $componentTypes,
        ComponentTypeResolverInterface $componentTypeResolver,
        LoggerInterface $logger
    ) {
        $this->componentRepository = $templateComponentRepository;
        $this->componentTypes = $componentTypes;
        $this->componentTypeResolver = $componentTypeResolver;
        $this->logger = $logger;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $componentChoices = [];

        foreach ($this->componentTypes->getComponentTypes() as $componentType) {
            if ($componentType instanceof TemplateComponentTypeFormType) {
                continue;
            }

            $componentChoices[$componentType->getTitle()] = $componentType->getName();
        }

        $builder
            ->add('name', PageComponentNameType::class, [
                'choices' => $componentChoices,
                'required' => true,
                'label' => 'Компонент',
                'placeholder' => '',
                'attr' => [
                    'class' => 'field-select page-component-name-select',
                    'data-widget' => 'select2',
                    'data-placeholder' => 'Выберите компонент',
                    'data-select' => 'true',
                ]
            ])
            ->add('order', IntegerType::class, [
                'required' => true,
                'label' => 'Позиция',
                'attr' => [
                    'positionable' => 'positionable',
                    'value' => 1,
                ],
            ])
            ->add('isPublished', CheckboxType::class, [
                'label' => 'Компонент активен',
                'attr' => [
                    'checked' => 'checked',
                ],
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($data instanceof TemplateComponent) {
                $this->addComponentToForm($form, $data->getName()->getValue());
                $form
                    ->add('isPublished', CheckboxType::class, [
                        'label' => 'Компонент активен'
                    ])
                    ->add('order', IntegerType::class, [
                        'required' => true,
                        'label' => 'Позиция',
                        'attr' => [
                            'positionable' => 'positionable',
                        ],
                    ]);
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if (!array_key_exists('name', $data)) {
                return;
            }

            $this->addComponentToForm($form, $data['name']);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => TemplateComponent::class,
            'empty_data' => function (FormInterface $form) {
                return new TemplateComponent(
                    $this->componentRepository->getNextIdentity(),
                    null,
                    new PageComponentName(''),
                    $form->getData()['data'] ?? [],
                    $form->getData()['order'] ?? 1,
                );
            },
        ]);
    }

    private function addComponentToForm(FormInterface $form, string $componentName): void
    {
        try {
            $componentType = $this->componentTypeResolver->resolveByName($componentName);

            $form->add('data', get_class($componentType), [
                'label' => 'Данные'
            ]);
        } catch (ComponentTypeNotFoundException $e) {
             $this->logger->warning(
                 'Component type not found by name',
                 ['name' => $componentName, 'errorMessage' => $e->getMessage()]
             );
        }
    }
}
