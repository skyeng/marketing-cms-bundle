<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Validator\Constraints;

use Skyeng\MarketingCmsBundle\Domain\Entity\Field;
use Skyeng\MarketingCmsBundle\Domain\Entity\FieldType;
use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Repository\FieldRepository\FieldRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\FieldConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\ModelsConfiguration;
use Skyeng\MarketingCmsBundle\Domain\Service\PropertyName\PropertyNameResolverInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueFieldValidator extends ConstraintValidator
{
    private const SUPPORTED_TYPES = [FieldType::TEXT, FieldType::BOOLEAN, FieldType::INTEGER];

    public function __construct(
        private FieldRepositoryInterface $fieldRepository,
        private ModelsConfiguration $modelsConfiguration,
        private PropertyNameResolverInterface $propertyNameResolver
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        $model = $value;

        if (!$constraint instanceof UniqueField) {
            throw new UnexpectedTypeException($constraint, UniqueField::class);
        }

        if (!$model instanceof Model) {
            throw new UnexpectedTypeException($model, Model::class);
        }

        $fieldConfig = $this->getFieldConfig($model, $constraint);

        if (!$fieldConfig instanceof FieldConfig) {
            throw new UnexpectedTypeException($constraint->fieldName, FieldConfig::class);
        }

        if (!in_array($fieldConfig->getType(), self::SUPPORTED_TYPES, true)) {
            throw new ConstraintDefinitionException(sprintf('Supported field with type %s, got %s', implode(',', self::SUPPORTED_TYPES), $fieldConfig->getType()));
        }

        if ($constraint->modelName && $constraint->modelName !== $model->getName()) {
            return;
        }

        $fields = array_filter(
            $model->getFields()->toArray(),
            static fn (Field $field): bool => $field->getName() === $constraint->fieldName,
        );

        if ($fields === []) {
            return;
        }

        $excludedFieldIds = array_map(
            static fn (Field $field): Id => $field->getId(),
            $fields,
        );

        $notUniqueFields = $this->fieldRepository->findByName(
            $constraint->fieldName,
            $this->getCurrentValue($model, $fieldConfig),
            $excludedFieldIds,
            $constraint->modelName
        );

        // если обновляем entity
        if ($notUniqueFields === []) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setCode(UniqueField::NOT_UNIQUE_ERROR)
            ->setCause($notUniqueFields)
            ->addViolation();
    }

    private function getFieldConfig(Model $model, UniqueField $constraint): ?FieldConfig
    {
        $modelConfig = $this->modelsConfiguration->getModelConfig($model->getName());

        return $modelConfig->findFieldConfig($constraint->fieldName);
    }

    private function getCurrentValue(Model $model, FieldConfig $fieldConfig): ?string
    {
        $fieldName = $this->propertyNameResolver->getPropertyNameValue($fieldConfig);

        $value = $model->__get($fieldName);

        return $value === null ? null : (string) $value;
    }
}
