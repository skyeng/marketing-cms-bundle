<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Skyeng\MarketingCmsBundle\Utils\Rector\Set\MarketingCmsSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        MarketingCmsSetList::VER_32,
        MarketingCmsSetList::VER_33,
        MarketingCmsSetList::VER_34,
        MarketingCmsSetList::VER_40,
    ]);
};
