<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Redirect\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"result"}
 * )
 */
class GetRedirectsV1ResultDto
{
    /**
     * Редиректы.
     *
     * @var GetRedirectsV1ResultItemDto[]
     */
    public array $result = [];
}
