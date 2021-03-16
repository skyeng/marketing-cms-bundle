<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form;

use Skyeng\MarketingCmsBundle\Domain\Entity\PageCustomMetaTag;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageCustomMetaTagRepository\PageCustomMetaTagRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageCustomMetaTagType extends AbstractType
{
    /**
     * @var PageCustomMetaTagRepositoryInterface
     */
    private $customMetaTagRepository;

    public function __construct(PageCustomMetaTagRepositoryInterface $customMetaTagRepository)
    {
        $this->customMetaTagRepository = $customMetaTagRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('property')
            ->add('content', TextType::class, [
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => PageCustomMetaTag::class,
            'empty_data' => function (FormInterface $form) {
                return new PageCustomMetaTag(
                    $this->customMetaTagRepository->getNextIdentity(),
                    null,
                    $form->getData()['name'] ?? '',
                    $form->getData()['property'] ?? '',
                    $form->getData()['content'] ?? '',
                );
            },
        ]);
    }
}
