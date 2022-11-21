<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Template;

use Skyeng\MarketingCmsBundle\Application\Cms\Template\Assembler\CloneTemplate\CloneTemplateResultAssembler;
use Skyeng\MarketingCmsBundle\Application\Cms\Template\Assembler\GetTemplatesV1ResultAssemblerInterface;
use Skyeng\MarketingCmsBundle\Application\Cms\Template\Dto\CloneTemplate\CloneTemplateRequestDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Template\Dto\CloneTemplate\CloneTemplateResultDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Template\Dto\GetTemplatesV1ResultDto;
use Skyeng\MarketingCmsBundle\Domain\Factory\Template\TemplateFactoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\TemplateRepositoryInterface;

class TemplateService
{
    public function __construct(
        private TemplateRepositoryInterface $templateRepository,
        private GetTemplatesV1ResultAssemblerInterface $getTemplatesV1ResultAssembler,
        private TemplateFactoryInterface $templateFactory,
        private CloneTemplateResultAssembler $cloneTemplateResultAssembler
    ) {
    }

    public function getTemplates(): GetTemplatesV1ResultDto
    {
        $templates = $this->templateRepository->getAll();

        return $this->getTemplatesV1ResultAssembler->assemble(...$templates);
    }

    public function cloneTemplate(CloneTemplateRequestDto $dto): CloneTemplateResultDto
    {
        $template = $this->templateRepository->getById($dto->id);
        $newTemplate = $this->templateFactory->clone($template);
        $this->templateRepository->save($newTemplate);

        return $this->cloneTemplateResultAssembler->assemble($newTemplate);
    }
}
