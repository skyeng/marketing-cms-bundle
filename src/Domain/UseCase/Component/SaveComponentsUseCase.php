<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\UseCase\Component;

use JsonException;
use Skyeng\MarketingCmsBundle\Domain\Entity\Component;
use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Domain\Factory\Component\ComponentFactoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\ComponentRepository\ComponentRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\ModelRepository\ModelRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Event\PreUpdateHookEvent;
use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Manager\HookManagerInterface;
use Skyeng\MarketingCmsBundle\Domain\UseCase\Component\Dto\ComponentsCollection;
use Skyeng\MarketingCmsBundle\Domain\UseCase\Component\Dto\UpdatedComponentDto;
use Skyeng\MarketingCmsBundle\Domain\UseCase\Component\Dto\UpdatedComponentsDto;
use Skyeng\MarketingCmsBundle\Domain\UseCase\Component\Exception\InvalidParameterException;

class SaveComponentsUseCase
{
    private const TEMPLATE_ID = 'template';

    public function __construct(
        private ComponentRepositoryInterface $componentRepository,
        private ComponentFactoryInterface $componentFactory,
        private HookManagerInterface $hookManager,
        private ModelRepositoryInterface $modelRepository,
    ) {
    }

    /**
     * @throws InvalidParameterException
     */
    public function __invoke(Model $model, UpdatedComponentsDto $updatedComponentsDto): void
    {
        $previousComponents = $this->componentRepository->getByModel($model);

        $previousComponentsCollection = new ComponentsCollection(...$previousComponents);

        $resultComponents = [];

        foreach ($updatedComponentsDto->toUpdatedComponentDtoArray() as $updatedComponent) {
            // if is a new component
            if ($updatedComponent->getId() === null) {
                $resultComponents[] = $this->componentFactory->create(
                    $updatedComponent->getField(),
                    $updatedComponent->getComponentName(),
                    $this->extractDataOrThrowException($updatedComponent),
                    $updatedComponent->getOrder(),
                    $updatedComponent->isPublished(),
                );

                continue;
            }

            $existComponent = $previousComponentsCollection->findById($updatedComponent->getId());

            if (!$existComponent instanceof Component) {
                throw new InvalidParameterException(sprintf('Component with id «%s» not found', $updatedComponent->getId()), sprintf('components_%s', $updatedComponent->getOrder()));
            }

            $existComponent->setName($updatedComponent->getComponentName());
            $existComponent->setIsPublished($updatedComponent->isPublished());
            $existComponent->setData($this->extractDataOrThrowException($updatedComponent));
            $existComponent->setOrder($updatedComponent->getOrder());

            $resultComponents[] = $existComponent;
            $previousComponentsCollection->removeById($updatedComponent->getId());
        }

        if ($previousComponentsCollection->count() > 0) {
            $this->componentRepository->remove(...$previousComponentsCollection->toArray());
        }

        $this->componentRepository->save(...$resultComponents);

        $this->hookManager->handle(new PreUpdateHookEvent($model));
        $this->modelRepository->save($model);
    }

    /**
     * @throws InvalidParameterException
     */
    private function extractDataOrThrowException(UpdatedComponentDto $componentDto): array
    {
        $isTemplateComponent = $componentDto->getComponentName()->isTemplateName();

        if ($isTemplateComponent) {
            if (empty($componentDto->getTemplateId())) {
                throw new InvalidParameterException('Must contains «templateId» for component with flag «isTemplate», got empty value', sprintf('components_%s_templateId', $componentDto->getOrder()), );
            }

            return [
                self::TEMPLATE_ID => $componentDto->getTemplateId(),
            ];
        }

        if (empty($componentDto->getJsonData())) {
            return [];
        }

        try {
            return (array) json_decode($componentDto->getJsonData(), true, 20, \JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new InvalidParameterException($e->getMessage(), sprintf('components_%s_data', $componentDto->getOrder()), );
        }
    }
}
