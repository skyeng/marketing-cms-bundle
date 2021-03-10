<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Service\Validator;

use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

class RequestFormValidationHelper
{
    /**
     * @param FormInterface $form
     * @throws ValidationException
     */
    public function validate(FormInterface $form): void
    {
        if ($form->isValid()) {
            return;
        }

        $errors = $this->getErrorMessages($form);
        throw new ValidationException(
            'Ошибка заполнения формы',
            Response::HTTP_BAD_REQUEST,
            null,
            $errors
        );
    }

    public function getErrorMessages(FormInterface $form): array
    {
        $errors = [];
        $formName = $form->getName();

        foreach ($form->getErrors(true, true) as $i => $error) {
            $elementName = $error->getOrigin()->getName();
            if ($elementName !== $formName) {
                $elementName = $formName . '_' . $elementName;
            }
            $errors[$elementName] = $error->getMessage();
        }

        return $errors;
    }
}
