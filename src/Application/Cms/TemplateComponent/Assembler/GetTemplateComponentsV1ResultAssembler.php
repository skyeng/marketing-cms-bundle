<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent\Assembler;

use Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent\Dto\Get\GetTemplateComponentsV1ResultDto;
use Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent\Dto\Get\TemplateComponentDto;
use Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent\Dto\Get\TemplateComponentsDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\Domain\Entity\TemplateComponent;

class GetTemplateComponentsV1ResultAssembler implements GetTemplateComponentsV1ResultAssemblerInterface
{
    public function assemble(Template $template): GetTemplateComponentsV1ResultDto
    {
        $templateComponents = [];

        foreach ($template->getComponents() as $templateComponent) {
            /** @var TemplateComponent $templateComponent */
            $templateComponents[] = new TemplateComponentDto(
                $templateComponent->getId()->getValue(),
                $templateComponent->getName()->getValue(),
                $templateComponent->getData(),
                $templateComponent->isPublished(),
                $templateComponent->getOrder(),
            );
        }

        return new GetTemplateComponentsV1ResultDto(new TemplateComponentsDto(
            $template->getName(),
            ...$templateComponents,
        ));
    }
}
