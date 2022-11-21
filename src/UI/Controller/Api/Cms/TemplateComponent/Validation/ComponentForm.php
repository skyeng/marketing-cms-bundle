<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\TemplateComponent\Validation;

use Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent\Dto\Save\ComponentDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ComponentForm extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'allow_extra_fields' => true,
            'data_class' => ComponentDto::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', TextType::class, [
                'constraints' => [
                    new Assert\Uuid(),
                ],
            ])
            ->add('selector', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('data', TextType::class)
            ->add('isTemplate', CheckboxType::class)
            ->add('templateId', TextType::class, [
                'constraints' => [
                    new Assert\Uuid(),
                ],
            ])
            ->add('isPublished', CheckboxType::class)
            ->add('order', TextType::class, [
                'constraints' => [
                    new Assert\Range(['min' => 0, 'max' => 1_000]),
                    new Assert\NotBlank(),
                ],
                'empty_data' => 1,
            ])
        ;
    }
}
