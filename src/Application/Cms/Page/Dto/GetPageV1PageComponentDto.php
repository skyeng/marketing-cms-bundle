<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Page\Dto;

use Swagger\Annotations as SWG;

class GetPageV1PageComponentDto
{
    /**
     * Название компонента
     * @var string
     * @SWG\Property(example="html-component")
     */
    public $name;

    /**
     * Параметры компонента
     * @var array
     * @SWG\Property(type="string[]", example="{html: 'data'}")
     */
    public $data;

    /**
     * Позиция компонента
     * @var int
     * @SWG\Property(example="1")
     */
    public $order;

    public function __construct(string $name, array $data, int $order)
    {
        $this->name = $name;
        $this->data = $data;
        $this->order = $order;
    }
}
