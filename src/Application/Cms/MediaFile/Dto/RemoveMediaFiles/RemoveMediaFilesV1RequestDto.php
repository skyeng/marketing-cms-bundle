<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Dto\RemoveMediaFiles;

use OpenApi\Annotations as OA;

class RemoveMediaFilesV1RequestDto
{
    /**
     * @var string[]
     *
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    public array $ids = [];
}
