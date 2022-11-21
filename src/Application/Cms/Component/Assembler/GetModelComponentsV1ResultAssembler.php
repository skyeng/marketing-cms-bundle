<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Component\Assembler;

use Skyeng\MarketingCmsBundle\Application\Cms\Component\Dto\Get\ComponentDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Component\Dto\Get\ComponentsDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Component\Dto\Get\GetModelComponentsV1ResultDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Component\Dto\Get\TemplateComponentDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Component\Dto\Get\TemplateDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\Component;
use Skyeng\MarketingCmsBundle\Domain\Entity\TemplateComponent;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\Exception\TemplateNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\TemplateRepositoryInterface;

final class GetModelComponentsV1ResultAssembler implements GetModelComponentsV1ResultAssemblerInterface
{
    public function __construct(private TemplateRepositoryInterface $templateRepository)
    {
    }

    public function assemble(Component ...$components): GetModelComponentsV1ResultDto
    {
        $resultComponents = [];

        foreach ($components as $component) {
            if ($this->isTemplateComponent($component)) {
                $templateDto = $this->createTemplateDto($component);

                if (!$templateDto instanceof TemplateDto) {
                    continue;
                }
            }

            $resultComponents[] = new ComponentDto(
                $component->getId()->getValue(),
                $component->getName()->getValue(),
                $component->getData(),
                $this->isTemplateComponent($component),
                $component->isPublished(),
                $component->getOrder(),
                $templateDto ?? null,
            );
        }

        return new GetModelComponentsV1ResultDto(
            new ComponentsDto(...$resultComponents)
        );
    }

    private function createTemplateDto(Component $component): ?TemplateDto
    {
        if (!$component->getName()->isTemplateName()) {
            return null;
        }

        $templateId = $component->getData()['template'] ?? null;

        if (empty($templateId)) {
            return null;
        }

        try {
            $template = $this->templateRepository->getById($templateId, true);
        } catch (TemplateNotFoundException) {
            return null;
        }

        return new TemplateDto(
            $template->getId()->getValue(),
            $template->getName(),
            ...array_map(
                static fn (TemplateComponent $component): TemplateComponentDto => new TemplateComponentDto(
                    $component->getId()->getValue(),
                    $component->getName()->getValue(),
                    $component->getData(),
                    $component->isPublished(),
                    $component->getOrder(),
                ),
                $template->getComponents()->toArray(),
            ),
        );
    }

    private function isTemplateComponent(Component $component): bool
    {
        return $component->getName()->isTemplateName();
    }
}
