<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Template\Dto\CloneTemplate;

use OpenApi\Annotations as OA;

class CloneTemplateRequestDto
{
    /**
     * @OA\Property(example="26f5cd3e-4c19-47f2-81a7-3a9f44c07182", required="true")
     */
    public string $id;
}
