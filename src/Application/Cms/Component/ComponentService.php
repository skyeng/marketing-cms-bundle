<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Component;

use Skyeng\MarketingCmsBundle\Application\Cms\Component\Assembler\GetModelComponentsV1ResultAssemblerInterface;
use Skyeng\MarketingCmsBundle\Application\Cms\Component\Dto\Get\GetModelComponentsV1RequestDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Component\Dto\Get\GetModelComponentsV1ResultDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Component\Dto\Save\ComponentDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Component\Dto\Save\SaveModelComponentsV1RequestDto;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Skyeng\MarketingCmsBundle\Domain\Entity\Field;
use Skyeng\MarketingCmsBundle\Domain\Entity\FieldType;
use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ComponentName;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Repository\ComponentRepository\ComponentRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\ModelRepository\Exception\ModelNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\ModelRepository\ModelRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\UseCase\Component\Dto\UpdatedComponentDto;
use Skyeng\MarketingCmsBundle\Domain\UseCase\Component\Dto\UpdatedComponentsDto;
use Skyeng\MarketingCmsBundle\Domain\UseCase\Component\Exception\InvalidParameterException;
use Skyeng\MarketingCmsBundle\Domain\UseCase\Component\SaveComponentsUseCase;

class ComponentService
{
    public function __construct(
        private SaveComponentsUseCase $saveComponentsUseCase,
        private GetModelComponentsV1ResultAssemblerInterface $getModelComponentsV1ResultAssembler,
        private ModelRepositoryInterface $modelRepository,
        private ComponentRepositoryInterface $componentRepository,
    ) {
    }

    public function getComponentsByModel(GetModelComponentsV1RequestDto $dto): GetModelComponentsV1ResultDto
    {
        $model = $this->modelRepository->getById($dto->modelId);
        $components = $this->componentRepository->getByModel($model);

        return $this->getModelComponentsV1ResultAssembler->assemble(...$components);
    }

    public function saveComponentsByModel(SaveModelComponentsV1RequestDto $requestDto): void
    {
        try {
            $model = $this->modelRepository->getById($requestDto->modelId);

            $this->saveComponentsUseCase->__invoke(
                $model,
                $this->makeUpdatedComponentsDto(
                    $this->getComponentsFieldFromModel($model),
                    $requestDto->components ?? [],
                ),
            );
        } catch (ModelNotFoundException) {
            throw ValidationException::fromErrors(['modelId' => sprintf('Model with id «%s» not found.', $requestDto->modelId)]);
        } catch (InvalidParameterException $e) {
            throw ValidationException::fromErrors([$e->getParameterName() => $e->getMessage()]);
        }
    }

    /**
     * @param ComponentDto[] $components
     */
    private function makeUpdatedComponentsDto(Field $field, array $components): UpdatedComponentsDto
    {
        $updatedComponents = array_map(
            static fn (ComponentDto $componentDto): UpdatedComponentDto => new UpdatedComponentDto(
                $componentDto->id === null ? null : new Id($componentDto->id),
                new ComponentName(
                    $componentDto->isTemplate
                        ? ComponentName::TEMPLATE_NAME
                        : $componentDto->selector
                ),
                $field,
                $componentDto->data,
                $componentDto->templateId,
                $componentDto->isPublished,
                $componentDto->order,
            ),
            $components,
        );

        return new UpdatedComponentsDto(...$updatedComponents);
    }

    private function getComponentsFieldFromModel(Model $model): Field
    {
        foreach ($model->getFields() as $field) {
            if ($field->getType() === FieldType::COMPONENTS) {
                return $field;
            }
        }

        throw new InvalidParameterException(sprintf('Model with id «%s» does not support components.', $model->getId()), 'modelId', );
    }
}
