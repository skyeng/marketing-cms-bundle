<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Builder;

use DateTimeImmutable;
use DateTimeInterface;
use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\Redirect;
use Skyeng\MarketingCmsBundle\Domain\Entity\Resource as ResourceEntity;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class RedirectBuilder
{
    private Id $id;

    private ResourceEntity $resource;

    private const TARGET_URL = 'https://skyeng.ru';

    private const HTTP_CODE = 301;

    private DateTimeInterface $createdAt;

    private function __construct()
    {
        $this->id = new Id(Uuid::uuid4()->toString());
        $this->resource = ResourceBuilder::resource()->build();
        $this->createdAt = new DateTimeImmutable('now');
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
            self::TARGET_URL,
            self::HTTP_CODE,
            $this->createdAt,
        );
    }
}
