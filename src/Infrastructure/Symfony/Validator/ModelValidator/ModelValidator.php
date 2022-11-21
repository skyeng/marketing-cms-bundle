<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Validator\ModelValidator;

use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\FieldConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\ModelConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\ModelsConfigurationInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Validator\Exception\ModelNotValidException;
use Skyeng\MarketingCmsBundle\Domain\Service\Validator\ModelValidatorInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ModelValidator implements ModelValidatorInterface
{
    public function __construct(
        private ModelsConfigurationInterface $modelsConfiguration,
        private ValidatorInterface $validator,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function validate(Model $model): void
    {
        $modelConfig = $this->modelsConfiguration->getModelConfig($model->getName());

        $violations = $this->validator->validate($model);

        if ($violations->count() > 0) {
            $errors = [];

            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            throw new ModelNotValidException(errors: $errors);
        }

        foreach ($model->getFields() as $field) {
            $fieldConfig = $this->getFieldConfig($modelConfig, $field->getName());

            if (empty($field->getValue()) && $fieldConfig->isRequired()) {
                throw new ModelNotValidException(errors: [$field->getName() => 'Поле является обязательным']);
            }
        }
    }

    /**
     * @throws ModelNotValidException
     */
    private function getFieldConfig(ModelConfig $modelConfig, string $fieldName): FieldConfig
    {
        $fieldConfig = $modelConfig->findFieldConfig($fieldName);

        if (!$fieldConfig instanceof FieldConfig) {
            throw new ModelNotValidException(errors: [$fieldName => 'Поле не описано в конфигурации']);
        }

        return $fieldConfig;
    }
}
