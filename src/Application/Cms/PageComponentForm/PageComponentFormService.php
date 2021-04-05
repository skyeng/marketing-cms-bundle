<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\PageComponentForm;

use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentForm\Assembler\GetPageComponentFormV1ResultAssemblerInterface;
use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentForm\Dto\GetPageComponentFormRequestDto;
use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentForm\Dto\GetPageComponentFormResultDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\PageComponentName;

class PageComponentFormService
{
    /**
     * @var GetPageComponentFormV1ResultAssemblerInterface
     */
    private $assembler;

    public function __construct(GetPageComponentFormV1ResultAssemblerInterface $assembler)
    {
        $this->assembler = $assembler;
    }

    public function getPageComponentForm(GetPageComponentFormRequestDto $dto): GetPageComponentFormResultDto
    {
        $pageComponent = new PageComponent(
            new Id('1'),
            null,
            new PageComponentName($dto->name),
            [],
            1
        );

        return $this->assembler->assemble($pageComponent);
    }
}
