<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form;

use Skyeng\MarketingCmsBundle\Domain\Entity\PageComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\PageComponentName;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageComponentRepository\PageComponentRepositoryInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\ComponentTypes\HTMLComponentType;
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
                'choices' => ['HTML' => PageComponentName::HTML_COMPONENT],
                'required' => true,
                'label' => 'Компонент',
                'attr' => [
                    'class' => 'field-select',
                    'data-widget' => 'select2',
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
            ])
            ->add('data', HTMLComponentType::class, [
                'label' => 'Данные'
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, static function (FormEvent $event) {
            $component = $event->getData();
            $form = $event->getForm();

            if ($component instanceof PageComponent) {
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
