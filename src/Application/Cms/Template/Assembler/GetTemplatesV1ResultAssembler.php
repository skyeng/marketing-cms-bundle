<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Template\Assembler;

use Skyeng\MarketingCmsBundle\Application\Cms\Template\Dto\GetTemplatesV1ResultDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Template\Dto\TemplateDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\Template;

class GetTemplatesV1ResultAssembler implements GetTemplatesV1ResultAssemblerInterface
{
    public function assemble(Template ...$templates): GetTemplatesV1ResultDto
    {
        $result = new GetTemplatesV1ResultDto();

        $result->result = [];

        foreach ($templates as $template) {
            $result->result[] = new TemplateDto(
                $template->getId()->getValue(),
                $template->getName(),
                true,
            );
        }

        return $result;
    }
}
