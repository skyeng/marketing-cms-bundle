<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Pages\ClonePage;

use Skyeng\MarketingCmsBundle\Application\Pages\ClonePage\Assembler\ClonePageResultAssembler;
use Skyeng\MarketingCmsBundle\Application\Pages\ClonePage\Dto\ClonePageRequestDto;
use Skyeng\MarketingCmsBundle\Application\Pages\ClonePage\Dto\ClonePageResultDto;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageRepository\PageRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\ClonePageService\ClonePageServiceInterface;

class ClonePageService
{
    /**
     * @var ClonePageServiceInterface
     */
    private $clonePageService;

    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var ClonePageResultAssembler
     */
    private $resultAssembler;

    public function __construct(
        PageRepositoryInterface $pageRepository,
        ClonePageServiceInterface $clonePageService,
        ClonePageResultAssembler $resultAssembler
    ) {
        $this->clonePageService = $clonePageService;
        $this->pageRepository = $pageRepository;
        $this->resultAssembler = $resultAssembler;
    }

    public function clonePage(ClonePageRequestDto $dto): ClonePageResultDto
    {
        $page = $this->pageRepository->getById($dto->id);
        $clonedPage = $this->clonePageService->clone($page);

        return $this->resultAssembler->assemble($clonedPage);
    }
}
