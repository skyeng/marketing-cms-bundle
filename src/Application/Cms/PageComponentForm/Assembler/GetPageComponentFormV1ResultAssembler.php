<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\PageComponentForm\Assembler;

use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentForm\Dto\GetPageComponentFormResultDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageComponent;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\PageComponentType;
use Symfony\Component\Form\FormFactoryInterface;

class GetPageComponentFormV1ResultAssembler implements GetPageComponentFormV1ResultAssemblerInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formBuilder;

    public function __construct(FormFactoryInterface $formBuilder)
    {
        $this->formBuilder = $formBuilder;
    }

    public function assemble(PageComponent $component): GetPageComponentFormResultDto
    {
        $form = $this->formBuilder->create(PageComponentType::class, $component);

        $resultDto = new GetPageComponentFormResultDto();
        $resultDto->result = $form;

        return $resultDto;
    }
}
