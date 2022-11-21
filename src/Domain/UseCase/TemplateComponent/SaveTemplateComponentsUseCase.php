<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\UseCase\TemplateComponent;

use Skyeng\MarketingCmsBundle\Domain\Entity\TemplateComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Factory\TemplateComponent\TemplateComponentFactoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\ModelRepository\Exception\ModelRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Repository\ModelRepository\ModelRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateComponentRepository\TemplateComponentRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\Exception\TemplateNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\TemplateRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Event\PreUpdateHookEvent;
use Skyeng\MarketingCmsBundle\Domain\Service\Model\ModelHookEventResolverInterface;
use Skyeng\MarketingCmsBundle\Domain\UseCase\TemplateComponent\Dto\UpdatedTemplateComponentDto;
use Skyeng\MarketingCmsBundle\Domain\UseCase\TemplateComponent\Exception\InvalidParameterException;

class SaveTemplateComponentsUseCase
{
    public function __construct(
        private TemplateComponentRepositoryInterface $componentRepository,
        private TemplateRepositoryInterface $templateRepository,
        private TemplateComponentFactoryInterface $componentFactory,
        private ModelRepositoryInterface $modelRepository,
        private ModelHookEventResolverInterface $modelHookEventResolver,
    ) {
    }

    /**
     * @throws InvalidParameterException
     */
    public function __invoke(Id $templateId, UpdatedTemplateComponentDto ...$updatedComponents): void
    {
        $resultComponents = [];
        $previousComponents = [];

        try {
            $template = $this->templateRepository->getById($templateId->getValue());
        } catch (TemplateNotFoundException) {
            throw new InvalidParameterException(sprintf('Template with id «%s» not found.', $templateId->getValue()), 'templateId', );
        }

        foreach ($this->componentRepository->getByTemplate($template) as $component) {
            $previousComponents[$component->getId()->getValue()] = $component;
        }

        foreach ($updatedComponents as $updatedComponent) {
            // if is a new component
            if ($updatedComponent->getId() === null) {
                $resultComponents[] = $this->componentFactory->create(
                    $template,
                    $updatedComponent->getComponentName(),
                    $this->extractDataOrThrowException($updatedComponent),
                    $updatedComponent->getOrder(),
                    $updatedComponent->isPublished(),
                );

                continue;
            }

            $existComponent = $previousComponents[$updatedComponent->getId()->getValue()] ?? null;

            if (!$existComponent instanceof TemplateComponent) {
                throw new InvalidParameterException(sprintf('Could not get exists component for Template with id «%s». Component with id «%s» not found', $template->getId(), $updatedComponent->getId()), sprintf('components_%s', $updatedComponent->getOrder()), );
            }

            $existComponent->setName($updatedComponent->getComponentName());
            $existComponent->setIsPublished($updatedComponent->isPublished());
            $existComponent->setData($this->extractDataOrThrowException($updatedComponent));
            $existComponent->setOrder($updatedComponent->getOrder());

            $resultComponents[] = $existComponent;
            unset($previousComponents[$updatedComponent->getId()->getValue()]);
        }

        if ($previousComponents !== []) {
            $this->componentRepository->remove(...array_values($previousComponents));
        }

        $this->componentRepository->save(...$resultComponents);

        try {
            $modelIds = $this->modelRepository->getIdsByTemplate($templateId);

            if ($modelIds !== []) {
                $this->modelHookEventResolver->runEvents($modelIds, PreUpdateHookEvent::class);
            }
        } catch (ModelRepositoryException) {
        }
    }

    /**
     * @throws InvalidParameterException
     */
    private function extractDataOrThrowException(UpdatedTemplateComponentDto $componentDto): array
    {
        if (empty($componentDto->getJsonData())) {
            return [];
        }

        try {
            return (array) json_decode($componentDto->getJsonData(), true, 20, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new InvalidParameterException($e->getMessage(), sprintf('components_%s_data', $componentDto->getOrder()), );
        }
    }
}
