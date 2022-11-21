<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\DBAL\Connection;
use DomainException;

class LoaderForAbstractFixture extends Loader
{
    public function __construct(private Connection $connection)
    {
    }

    /**
     * {@inheritDoc}
     */
    protected function createFixture($class): FixtureInterface
    {
        $fixture = new $class($this->connection);

        if (!$fixture instanceof FixtureInterface) {
            throw new DomainException(sprintf('Class «%s» must implement FixtureInterface', $class));
        }

        return $fixture;
    }
}
