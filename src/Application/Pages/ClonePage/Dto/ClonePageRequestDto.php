<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Pages\ClonePage\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id"}
 * )
 */
class ClonePageRequestDto
{
    /**
     * @var string
     * @SWG\Property(example="26f5cd3e-4c19-47f2-81a7-3a9f44c07182")
     */
    public $id;
}
