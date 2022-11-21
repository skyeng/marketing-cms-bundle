<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests;

use Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\DataFixtures\LoaderForAbstractFixture;
use Codeception\Module\Symfony;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;

trait FixturesLoaderTrait
{
    public function haveFixtures(array $fixtures): void
    {
        /** @var Symfony $symfonyModule */
        $symfonyModule = $this->getModule('Symfony');

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $symfonyModule->_getContainer()->get('doctrine.orm.entity_manager');
        $connection = $entityManager->getConnection();
        $fixturesLoader = new LoaderForAbstractFixture($connection);
        $purger = new ORMPurger($entityManager);
        $fixturesExecutor = new ORMExecutor($entityManager, $purger);

        foreach ($fixtures as $fixture) {
            $fixtureInstance = new $fixture($connection);
            $fixturesLoader->addFixture($fixtureInstance);
        }

        $fixturesExecutor->execute($fixturesLoader->getFixtures(), false);
    }
}
