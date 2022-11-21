<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Page\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"uri"}
 * )
 */
class GetPageV1RequestDto
{
    /**
     * @var string
     * @SWG\Property(example="/page")
     */
    public $uri;
}
