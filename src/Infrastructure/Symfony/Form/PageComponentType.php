<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form;

use Skyeng\MarketingCmsBundle\Domain\Entity\PageComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\PageComponentName;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageComponentRepository\PageComponentRepositoryInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\ComponentTypes\HTMLComponentType;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\ComponentTypes\SimpleFormComponentType;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\PageComponentNameType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageComponentType extends AbstractType
{
    private const COMPONENT_CLASS = [
        PageComponentName::HTML_COMPONENT => HTMLComponentType::class,
        PageComponentName::SIMPLE_FORM_COMPONENT => SimpleFormComponentType::class,
    ];

    private const COMPONENTS_LIST = [
        'HTML' => PageComponentName::HTML_COMPONENT,
        'Simple form' => PageComponentName::SIMPLE_FORM_COMPONENT,
    ];

    /**
     * @var PageComponentRepositoryInterface
     */
    private $componentRepository;

    public function __construct(PageComponentRepositoryInterface $componentRepository)
    {
        $this->componentRepository = $componentRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', PageComponentNameType::class, [
                'choices' => self::COMPONENTS_LIST,
                'required' => true,
                'label' => 'Компонент',
                'attr' => [
                    'class' => 'field-select page-component-name-select',
                    'data-widget' => 'select2',
                ]
            ])
            ->add('order', IntegerType::class, [
                'required' => true,
                'label' => 'Позиция',
                'attr' => [
                    'positionable' => 'positionable'
                ],
            ])
            ->add('isPublished', CheckboxType::class, [
                'label' => 'Компонент активен'
            ])
            ->add('data', HTMLComponentType::class, [
                'label' => 'Данные'
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, static function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($data instanceof PageComponent) {
                $form->add('data', self::COMPONENT_CLASS[$data->getName()->getValue()], [
                    'label' => 'Данные'
                ]);
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, static function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if (!array_key_exists('name', $data)) {
                return;
            }

            if (!in_array($data['name'], self::COMPONENTS_LIST, true)) {
                return;
            }

            $pageComponentName = new PageComponentName($data['name']);
            $form->add('data', self::COMPONENT_CLASS[$pageComponentName->getValue()], [
                'label' => 'Данные'
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => PageComponent::class,
            'empty_data' => function (FormInterface $form) {
                return new PageComponent(
                    $this->componentRepository->getNextIdentity(),
                    null,
                    new PageComponentName(PageComponentName::HTML_COMPONENT),
                    $form->getData()['data'] ?? [],
                    $form->getData()['order'] ?? 1,
                );
            },
        ]);
    }
}
