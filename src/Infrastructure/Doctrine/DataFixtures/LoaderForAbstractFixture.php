<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\DataFixtures;

use Doctrine\Common\DataFixtures\Loader;
use Doctrine\DBAL\Connection;

class LoaderForAbstractFixture extends Loader
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    protected function createFixture($class)
    {
        return new $class($this->connection);
    }
}
