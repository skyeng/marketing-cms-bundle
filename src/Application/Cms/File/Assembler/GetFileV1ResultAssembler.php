<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\File\Assembler;

use Skyeng\MarketingCmsBundle\Application\Cms\File\Dto\GetFileV1ResultDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\File;

class GetFileV1ResultAssembler implements GetFileV1ResultAssemblerInterface
{
    public function assemble(File $file): GetFileV1ResultDto
    {
        return new GetFileV1ResultDto(
            $file->getContent(),
            $file->getContentType()->getValue(),
            $file->getResource()->getUri()->getPathname(),
            $file->getCacheTime()->getValue(),
        );
    }
}
