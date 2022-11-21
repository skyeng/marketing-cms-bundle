<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Validator;

use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Skyeng\MarketingCmsBundle\Application\Service\Validator\ValidatorInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;

class Validator implements ValidatorInterface
{
    private const DATA_TYPES = [
        'array',
        'string',
        'NULL',
    ];

    public function __construct(
        private FormFactoryInterface $formFactory,
        private RequestFormValidationHelper $formValidationHelper
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function validate(string $formClass, mixed $data, object $model = null): void
    {
        $dataType = gettype($data);

        if (!in_array($dataType, self::DATA_TYPES, true)) {
            throw new ValidationException('Ошибка заполнения формы', Response::HTTP_BAD_REQUEST, null, ['data' => sprintf('Request data type must be %s, but got «%s»', implode(',', self::DATA_TYPES), $dataType)]);
        }

        $form = $this->formFactory->create($formClass, $model)->submit($data);
        $this->formValidationHelper->validate($form);
    }
}
