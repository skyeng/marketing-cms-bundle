<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Helper;

use Codeception\Module;
use Exception;
use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class Integrational extends Module
{
    use ContainerTrait;

    /**
     * @throws Exception
     */
    public function generateId(): Id
    {
        return new Id(Uuid::uuid4()->toString());
    }
}
