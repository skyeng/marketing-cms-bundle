<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Page\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"result"}
 * )
 */
class GetPageV1ResultDto
{
    /**
     * Страница
     * @var PageDto
     */
    public $result;
}
