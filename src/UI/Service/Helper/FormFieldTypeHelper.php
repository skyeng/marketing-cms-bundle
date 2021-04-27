<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Service\Helper;

class FormFieldTypeHelper
{
    public const TYPE_TEXT = 'text';
    public const TYPE_TEXTAREA = 'textarea';
    public const TYPE_INTEGER = 'integer';
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_CHOICE = 'choice';

    public const AVAILABLE_TYPES = [
        self::TYPE_TEXT => self::TYPE_TEXT,
        self::TYPE_TEXTAREA => self::TYPE_TEXTAREA,
        self::TYPE_INTEGER => self::TYPE_INTEGER,
        self::TYPE_BOOLEAN => self::TYPE_BOOLEAN,
        self::TYPE_CHOICE => self::TYPE_CHOICE,
    ];
}
