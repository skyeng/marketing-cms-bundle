<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Model\Dto;

class ModelRequest
{
    public string $id;
    public ?string $locale = null;

    public static function create(string $id, string $locale = null): self
    {
        $self = new self();
        $self->id = $id;
        $self->locale = $locale;

        return $self;
    }
}
