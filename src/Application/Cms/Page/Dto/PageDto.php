<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Page\Dto;

use Swagger\Annotations as SWG;

class PageDto
{
    /**
     * Заголовок страницы
     * @var string
     * @SWG\Property(example="Примеры задач по математике для 4 класса.")
     */
    public $title;

    /**
     * Uri
     * @var string
     * @SWG\Property(example="/page-math")
     */
    public $uri;

    /**
     * Шаблон страницы
     * @var string
     * @SWG\Property(example="skysmart-base")
     */
    public $layout;

    /**
     * Мета теги страницы
     * @var GetPageV1PageMetaTagDto[]
     */
    public $metaTags = [];

    /**
     * Компоненты страницы
     * @var GetPageV1PageComponentDto[]
     */
    public $components = [];

    public function __construct(string $title, string $uri, string $layout, array $metaTags, array $components)
    {
        $this->title = $title;
        $this->uri = $uri;
        $this->layout = $layout;
        $this->metaTags = $metaTags;
        $this->components = $components;
    }
}
