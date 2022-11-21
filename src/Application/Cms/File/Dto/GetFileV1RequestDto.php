<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\File\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"uri"}
 * )
 */
class GetFileV1RequestDto
{
    /**
     * @OA\Property(example="/test.txt")
     */
    public string $uri;
}
