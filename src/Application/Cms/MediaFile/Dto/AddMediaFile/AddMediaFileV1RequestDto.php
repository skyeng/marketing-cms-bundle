<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Dto\AddMediaFile;

use Symfony\Component\HttpFoundation\File\File;

class AddMediaFileV1RequestDto
{
    public File $file;
}
