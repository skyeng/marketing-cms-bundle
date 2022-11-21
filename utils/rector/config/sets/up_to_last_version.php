<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Skyeng\MarketingCmsBundle\Utils\Rector\Set\MarketingCmsSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        MarketingCmsSetList::UP_TO_32,
        MarketingCmsSetList::UP_TO_33,
        MarketingCmsSetList::UP_TO_34,
        MarketingCmsSetList::UP_TO_40,
    ]);
};
