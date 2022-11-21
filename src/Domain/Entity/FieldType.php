<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

class FieldType
{
    public const DATE_TIME = 'DateTime';
    public const BOOLEAN = 'Boolean';
    public const CHOICE = 'Choice';
    public const INTEGER = 'Integer';
    public const TEXTAREA = 'Textarea';
    public const TEXT = 'Text';
    public const COMPONENTS = 'Components';
}
