<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Page;

use Skyeng\MarketingCmsBundle\Application\Cms\Page\Dto\GetPageV1RequestDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Page\Dto\GetPageV1ResultDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Page\Assembler\GetPageV1ResultAssemblerInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageRepository\PageRepositoryInterface;

class PageService
{
    /**
     * @var GetPageV1ResultAssemblerInterface
     */
    private $getPageV1ResultAssembler;

    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    public function __construct(
        GetPageV1ResultAssemblerInterface $getPageV1ResultAssembler,
        PageRepositoryInterface $pageRepository
    ) {
        $this->getPageV1ResultAssembler = $getPageV1ResultAssembler;
        $this->pageRepository = $pageRepository;
    }

    public function getPage(
        GetPageV1RequestDto $dto
    ): GetPageV1ResultDto {
        $page = $this->pageRepository->getByUri($dto->uri, true);

        return $this->getPageV1ResultAssembler->assemble($page);
    }
}
