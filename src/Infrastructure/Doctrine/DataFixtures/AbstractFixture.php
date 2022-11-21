<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Exception;

abstract class AbstractFixture extends Fixture
{
    /**
     * @var string
     */
    public $tableName;

    /**
     * @var string
     */
    public $dataFile;

    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws Exception
     */
    public function load(ObjectManager $manager)
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
     */
    protected function getDataForInsert(): array
    {
        if (empty($this->dataFile)) {
            throw new Exception('$dataFile name property must be set');
        }

        if (file_exists($this->dataFile) === false) {
            throw new Exception(sprintf('File "%s" not exists', $this->dataFile));
        }

        return require $this->dataFile;
    }
}
