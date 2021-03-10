<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Builder;

use Skyeng\MarketingCmsBundle\Domain\Entity\Redirect;
use Skyeng\MarketingCmsBundle\Domain\Entity\Resource as ResourceEntity;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Ramsey\Uuid\Uuid;

class RedirectBuilder
{
    /**
     * @var Id
     */
    private $id;

    /**
     * @var ResourceEntity
     */
    private $resource;

    /**
     * @var string
     */
    private $targetUrl;

    /**
     * @var int
     */
    private $httpCode;

    private function __construct()
    {
        $this->id = new Id(Uuid::uuid4()->toString());
        $this->resource = ResourceBuilder::resource()->build();
        $this->targetUrl = 'https://skyeng.ru';
        $this->httpCode = 301;
    }

    public static function redirect(): self
    {
        return new self();
    }

    public function build(): Redirect
    {
        return new Redirect(
            $this->id,
            $this->resource,
            $this->targetUrl,
            $this->httpCode
        );
    }
}
