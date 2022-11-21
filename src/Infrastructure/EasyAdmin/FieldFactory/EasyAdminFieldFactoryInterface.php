<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\EasyAdmin\FieldFactory;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\FieldConfig;

interface EasyAdminFieldFactoryInterface
{
    public function supportedType(): string;

    public function create(FieldConfig $fieldConfig): FieldInterface;
}
