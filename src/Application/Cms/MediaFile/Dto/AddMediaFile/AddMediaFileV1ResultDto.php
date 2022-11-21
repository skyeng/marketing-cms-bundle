<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Dto\AddMediaFile;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"result"}
 * )
 */
class AddMediaFileV1ResultDto
{
    public function __construct(public ResultDto $result)
    {
    }
}
