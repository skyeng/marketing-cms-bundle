<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Controller\Api\Cms\Model\Validation;

use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Locale\LocaleConfigurationInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Paginator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class GetModelsV1Form extends AbstractType
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
            ->add('model', TextType::class, [
                'property_path' => 'modelName',
                'constraints' => [new NotBlank()],
            ])
            ->add('locale', TextType::class, [
                'empty_data' => $this->localeConfiguration->getDefaultLocale(),
            ])
            ->add('filters', CollectionType::class, [
                'allow_add' => true,
                'empty_data' => [],
            ])
            ->add('sorts', CollectionType::class, [
                'allow_add' => true,
                'empty_data' => [],
            ])
            ->add('page', IntegerType::class, [
                'empty_data' => '1',
            ])
            ->add('per_page', IntegerType::class, [
                'property_path' => 'perPage',
                'empty_data' => (string) Paginator::ITEMS_PER_PAGE,
            ]);
    }
}
