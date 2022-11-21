<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\File\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"uri"}
 * )
 */
class GetFileV1RequestDto
{
    /**
     * @var string
     * @SWG\Property(example="/test.txt")
     */
    public $uri;
}
