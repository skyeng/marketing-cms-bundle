<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Model\Dto;

class ModelsRequest
{
    public string $modelName;
    public ?string $locale = null;

    /**
     * @var array<string, string>
     */
    public array $filters = [];

    /**
     * @var array<string, string>
     */
    public $sorts = [];

    public int $page;
    public int $perPage;

    public static function create(
        string $modelName,
        string $locale = null,
        array $filters = [],
        array $sorts = [],
        int $page = 1,
        int $perPage = 10,
    ): self {
        $self = new self();
        $self->modelName = $modelName;
        $self->locale = $locale;
        $self->filters = $filters;
        $self->sorts = $sorts;
        $self->page = $page;
        $self->perPage = $perPage;

        return $self;
    }
}
