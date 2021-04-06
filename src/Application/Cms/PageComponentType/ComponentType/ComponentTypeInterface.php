<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\ComponentType;

use Symfony\Component\Form\FormTypeInterface;

interface ComponentTypeInterface extends FormTypeInterface
{
    public function getTitle(): string;

    public function getName(): string;
}
