<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Redirect\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"result"}
 * )
 */
class GetRedirectsV1ResultDto
{
    /**
     * Редиректы
     * @var GetRedirectsV1ResultItemDto[]
     */
    public $result;
}
