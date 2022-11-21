<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Exception;

abstract class AbstractFixture extends Fixture
{
    public string $tableName;
    public string $dataFile;

    public function __construct(private Connection $connection)
    {
    }

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getDataForInsert() as $data) {
            $this->connection->insert(
                $this->getTableName(),
                $data
            );
        }
    }

    /**
     * @throws Exception
     */
    protected function getTableName(): string
    {
        if (empty($this->tableName)) {
            throw new Exception('$tableName property must be set');
        }

        return $this->tableName;
    }

    /**
     * @throws Exception
     *
     * @return mixed[]
     */
    protected function getDataForInsert(): array
    {
        if (empty($this->dataFile)) {
            throw new Exception('$dataFile name property must be set');
        }

        if (!file_exists($this->dataFile)) {
            throw new Exception(sprintf('File "%s" not exists', $this->dataFile));
        }

        return require $this->dataFile;
    }
}
