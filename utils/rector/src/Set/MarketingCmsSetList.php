<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Utils\Rector\Set;

use Rector\Set\Contract\SetListInterface;

class MarketingCmsSetList implements SetListInterface
{
    public const VER_32 = __DIR__.'/../../config/sets/ver_32.php';
    public const VER_33 = __DIR__.'/../../config/sets/ver_33.php';
    public const VER_34 = __DIR__.'/../../config/sets/ver_34.php';
    public const VER_40 = __DIR__.'/../../config/sets/ver_40.php';

    public const UP_TO_LAST_VER = __DIR__.'/../../config/sets/up_to_last_ver.php';
}
