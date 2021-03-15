<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form;

use Skyeng\MarketingCmsBundle\Domain\Entity\PageComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\PageComponentName;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageComponentRepository\PageComponentRepositoryInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\ComponentTypes\HTMLComponentType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\DBAL\Types\Type;

class PageComponentType extends Type
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
                    'positionable' => 'positionable'
                ],
            ])
            ->add('isPublished', CheckboxType::class, [
                'label' => 'Компонент активен'
            ])
            ->add('data', HTMLComponentType::class, [
                'label' => 'Данные'
            ]);
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
