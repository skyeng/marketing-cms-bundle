<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests;

use Codeception\Module\Symfony;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\ORM\EntityManagerInterface;
use Skyeng\MarketingCmsBundle\Tests\DataFixtures\LoaderForAbstractFixture;

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
        $fixturesExecutor = new ORMExecutor($entityManager);

        foreach ($fixtures as $fixture) {
            $fixtureInstance = new $fixture($connection);
            $fixturesLoader->addFixture($fixtureInstance);
        }

        $fixturesExecutor->execute($fixturesLoader->getFixtures(), true);
    }
}
