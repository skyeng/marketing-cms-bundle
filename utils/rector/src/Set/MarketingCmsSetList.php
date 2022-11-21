<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Utils\Rector\Set;

use Rector\Set\Contract\SetListInterface;

class MarketingCmsSetList implements SetListInterface
{
    public const UP_TO_32 = __DIR__.'/../../config/sets/up_to_32.php';
    public const UP_TO_33 = __DIR__.'/../../config/sets/up_to_33.php';
    public const UP_TO_34 = __DIR__.'/../../config/sets/up_to_34.php';
    public const UP_TO_40 = __DIR__.'/../../config/sets/up_to_40.php';

    public const UP_TO_LAST_VERSION = __DIR__.'/../../config/sets/up_to_last_version.php';
}
