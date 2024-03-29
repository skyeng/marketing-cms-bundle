<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Unit\Domain\Entity\ValueObject;

use Codeception\Test\Unit;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Uri;
use Skyeng\MarketingCmsBundle\Domain\Exception\DomainException;
use Skyeng\MarketingCmsBundle\Tests\UnitTester;

class UriTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function testGetStringUriWithoutPath(): void
    {
        $paths = [
            '/path',
            '/path.txt',
            '/path/path',
            '/path/path.txt',
        ];

        foreach ($paths as $path) {
            $uri = new Uri($path);

            $this->assertSame($path, $uri->getValue());
        }
    }

    public function testGetStringUriWithPath(): void
    {
        $paths = [
            'path',
            'path.txt',
            '/',
        ];

        foreach ($paths as $path) {
            $this->tester->expectThrowable(
                DomainException::class,
                static fn (): Uri => new Uri($path)
            );
        }
    }
}
