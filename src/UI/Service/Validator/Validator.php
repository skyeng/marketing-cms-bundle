<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Service\Validator;

use Symfony\Component\Form\FormFactoryInterface;

class Validator implements ValidatorInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RequestFormValidationHelper
     */
    private $formValidationHelper;

    public function __construct(
        FormFactoryInterface $formFactory,
        RequestFormValidationHelper $formValidationHelper
    ) {
        $this->formFactory = $formFactory;
        $this->formValidationHelper = $formValidationHelper;
    }

    public function validate(string $formClass, array $data, object $model = null): void
    {
        $form = $this->formFactory->create($formClass, $model)->submit($data);
        $this->formValidationHelper->validate($form);
    }
}
