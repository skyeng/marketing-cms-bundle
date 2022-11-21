<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\Model\Validation;

use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Locale\LocaleConfigurationInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class GetModelV1Form extends AbstractType
{
    public function __construct(private LocaleConfigurationInterface $localeConfiguration)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', TextType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('locale', TextType::class, [
                'empty_data' => $this->localeConfiguration->getDefaultLocale(),
            ]);
    }
}
