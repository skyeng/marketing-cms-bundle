<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Redirect;

use Skyeng\MarketingCmsBundle\Application\Cms\Redirect\Dto\GetRedirectsV1ResultDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Redirect\Assembler\GetRedirectsV1ResultAssemblerInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\RedirectRepository\RedirectRepositoryInterface;

class RedirectService
{
    /**
     * @var RedirectRepositoryInterface
     */
    private $redirectRepository;

    /**
     * @var GetRedirectsV1ResultAssemblerInterface
     */
    private $getRedirectsV1ResultAssembler;

    public function __construct(
        RedirectRepositoryInterface $redirectRepository,
        GetRedirectsV1ResultAssemblerInterface $getRedirectsV1ResultAssembler
    ) {
        $this->redirectRepository = $redirectRepository;
        $this->getRedirectsV1ResultAssembler = $getRedirectsV1ResultAssembler;
    }

    public function getRedirects(): GetRedirectsV1ResultDto
    {
        $redirects = $this->redirectRepository->getAll();
        return $this->getRedirectsV1ResultAssembler->assemble($redirects);
    }
}
