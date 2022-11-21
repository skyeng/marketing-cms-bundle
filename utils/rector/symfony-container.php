<?php

declare(strict_types=1);

use Skyeng\MarketingCmsBundle\Utils\SymfonyApplication\Kernel;

$_SERVER['APP_RUNTIME_OPTIONS']['dotenv_path'] = 'utils/symfony-application/.env';

require_once dirname(__DIR__).'/../vendor/autoload_runtime.php';

return (new Kernel('test', false))->getContainer();
