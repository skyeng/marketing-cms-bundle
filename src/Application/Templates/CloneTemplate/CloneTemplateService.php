<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Templates\CloneTemplate;

use Skyeng\MarketingCmsBundle\Application\Templates\CloneTemplate\Assembler\CloneTemplateResultAssembler;
use Skyeng\MarketingCmsBundle\Application\Templates\CloneTemplate\Dto\CloneTemplateRequestDto;
use Skyeng\MarketingCmsBundle\Application\Templates\CloneTemplate\Dto\CloneTemplateResultDto;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\TemplateRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\CloneTemplateService\CloneTemplateServiceInterface;

class CloneTemplateService
{
    /**
     * @var CloneTemplateServiceInterface
     */
    private $cloneTemplateService;

    /**
     * @var TemplateRepositoryInterface
     */
    private $templateRepository;

    /**
     * @var CloneTemplateResultAssembler
     */
    private $resultAssembler;

    public function __construct(
        TemplateRepositoryInterface $templateRepository,
        CloneTemplateServiceInterface $cloneTemplateService,
        CloneTemplateResultAssembler $resultAssembler
    ) {
        $this->cloneTemplateService = $cloneTemplateService;
        $this->templateRepository = $templateRepository;
        $this->resultAssembler = $resultAssembler;
    }

    public function cloneTemplate(CloneTemplateRequestDto $dto): CloneTemplateResultDto
    {
        $template = $this->templateRepository->getById($dto->id);
        $clonedTemplate = $this->cloneTemplateService->clone($template);

        return $this->resultAssembler->assemble($clonedTemplate);
    }
}
