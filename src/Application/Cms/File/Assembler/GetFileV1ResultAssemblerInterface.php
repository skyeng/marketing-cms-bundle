<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\File\Assembler;

use Skyeng\MarketingCmsBundle\Application\Cms\File\Dto\GetFileV1ResultDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\File;

interface GetFileV1ResultAssemblerInterface
{
    public function assemble(File $file): GetFileV1ResultDto;
}
