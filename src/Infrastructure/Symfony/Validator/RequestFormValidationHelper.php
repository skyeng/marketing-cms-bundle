<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Validator;

use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

class RequestFormValidationHelper
{
    /**
     * @throws ValidationException
     */
    public function validate(FormInterface $form): void
    {
        if ($form->isValid()) {
            return;
        }

        $errors = $this->getErrorMessages($form);
        throw new ValidationException('Ошибка заполнения формы', Response::HTTP_BAD_REQUEST, null, $errors);
    }

    /**
     * @return array<string, string>
     */
    public function getErrorMessages(FormInterface $form): array
    {
        $errors = [];
        $formName = $form->getName();

        foreach ($form->getErrors(true) as $error) {
            $elementName = '';

            if ($error->getOrigin() instanceof FormInterface) {
                $elementName = $error->getOrigin()->getName();
            }

            if ($elementName !== $formName) {
                $elementName = $formName.'_'.$elementName;
            }
            $errors[$elementName] = $error->getMessage();
        }

        return $errors;
    }
}
