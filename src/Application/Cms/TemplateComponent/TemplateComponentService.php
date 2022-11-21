<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent;

use Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent\Assembler\GetTemplateComponentsV1ResultAssemblerInterface;
use Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent\Dto\Get\GetTemplateComponentsV1RequestDto;
use Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent\Dto\Get\GetTemplateComponentsV1ResultDto;
use Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent\Dto\Save\ComponentDto;
use Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent\Dto\Save\SaveTemplateComponentsV1RequestDto;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ComponentName;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\TemplateRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\UseCase\TemplateComponent\Dto\UpdatedTemplateComponentDto;
use Skyeng\MarketingCmsBundle\Domain\UseCase\TemplateComponent\Exception\InvalidParameterException;
use Skyeng\MarketingCmsBundle\Domain\UseCase\TemplateComponent\SaveTemplateComponentsUseCase;

class TemplateComponentService
{
    public function __construct(
        private TemplateRepositoryInterface $templateRepository,
        private GetTemplateComponentsV1ResultAssemblerInterface $getTemplateComponentsV1ResultAssembler,
        private SaveTemplateComponentsUseCase $saveTemplateComponentsUseCase
    ) {
    }

    public function getTemplateComponents(GetTemplateComponentsV1RequestDto $dto): GetTemplateComponentsV1ResultDto
    {
        $templateWithComponents = $this->templateRepository->getById($dto->templateId, true);

        return $this->getTemplateComponentsV1ResultAssembler->assemble($templateWithComponents);
    }

    public function saveComponents(SaveTemplateComponentsV1RequestDto $requestDto): void
    {
        $updatedComponents = array_map(
            static fn (ComponentDto $componentDto): UpdatedTemplateComponentDto => new UpdatedTemplateComponentDto(
                $componentDto->id === null ? null : new Id($componentDto->id),
                new ComponentName($componentDto->selector),
                $componentDto->data,
                $componentDto->isPublished,
                $componentDto->order,
            ),
            $requestDto->components ?? [],
        );

        try {
            $this->saveTemplateComponentsUseCase->__invoke(
                new Id($requestDto->templateId),
                ...$updatedComponents,
            );
        } catch (InvalidParameterException $e) {
            throw new ValidationException('Validation error', 400, null, [$e->getParameterName() => $e->getMessage()]);
        }
    }
}
