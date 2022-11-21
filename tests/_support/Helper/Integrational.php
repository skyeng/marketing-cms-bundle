<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Helper;

use Exception;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Ramsey\Uuid\Uuid;

class Integrational extends \Codeception\Module
{
    use ContainerTrait;

    /**
     * @return Id
     * @throws Exception
     */
    public function generateId(): Id
    {
        return new Id(Uuid::uuid4()->toString());
    }
}
