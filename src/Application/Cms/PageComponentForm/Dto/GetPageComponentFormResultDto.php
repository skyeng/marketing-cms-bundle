<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\PageComponentForm\Dto;

use Symfony\Component\Form\FormInterface;

class GetPageComponentFormResultDto
{
    /**
     * Форма компонента страницы
     * @var FormInterface
     */
    public $result;
}
