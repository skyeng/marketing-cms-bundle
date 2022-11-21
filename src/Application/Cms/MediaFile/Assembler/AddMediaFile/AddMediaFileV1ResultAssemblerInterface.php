<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Assembler\AddMediaFile;

use Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Dto\AddMediaFile\AddMediaFileV1ResultDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\MediaFile;

interface AddMediaFileV1ResultAssemblerInterface
{
    public function assemble(MediaFile $mediaFile): AddMediaFileV1ResultDto;
}
