<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Assembler\AddMediaFile;

use Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Dto\AddMediaFile\AddMediaFileV1ResultDto;
use Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Dto\AddMediaFile\ResultDto;
use Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Service\MediaFilePathResolver;
use Skyeng\MarketingCmsBundle\Domain\Entity\MediaFile;

class AddMediaFileV1ResultAssembler implements AddMediaFileV1ResultAssemblerInterface
{
    public function __construct(private MediaFilePathResolver $mediaFilePathResolver)
    {
    }

    public function assemble(MediaFile $mediaFile): AddMediaFileV1ResultDto
    {
        return new AddMediaFileV1ResultDto(
            new ResultDto(
                $mediaFile->getId()->getValue(),
                $this->mediaFilePathResolver->getFileUrl($mediaFile) ?? '',
            )
        );
    }
}
