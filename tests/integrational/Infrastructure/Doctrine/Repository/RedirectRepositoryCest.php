<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Integrational\Infrastructure\Doctrine\Repository;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Uri;
use Skyeng\MarketingCmsBundle\Domain\Repository\RedirectRepository\Exception\RedirectNotFoundException;
use Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository\RedirectRepository;
use Skyeng\MarketingCmsBundle\Tests\Builder\RedirectBuilder;
use Skyeng\MarketingCmsBundle\Tests\IntegrationalTester;

class RedirectRepositoryCest
{
    /**
     * @var RedirectRepository
     */
    private $repository;

    public function _before(IntegrationalTester $I)
    {
        $this->repository = $I->getContainerService(RedirectRepository::class);
    }

    public function testGetAll(IntegrationalTester $I): void
    {
        $redirect1 = RedirectBuilder::redirect()->build();
        $redirect2 = RedirectBuilder::redirect()->build();
        $this->repository->save($redirect1);
        $this->repository->save($redirect2);

        $redirects = $this->repository->getAll();

        $I->assertCount(2, $redirects);
        $I->assertEquals($redirect1->getId(), $redirects[0]->getId());
        $I->assertEquals($redirect2->getId(), $redirects[1]->getId());
    }

    public function testGetByPath(IntegrationalTester $I): void
    {
        $expectedRedirect = RedirectBuilder::redirect()->build();
        $this->repository->save($expectedRedirect);

        $redirect = $this->repository->getByUri($expectedRedirect->getResource()->getUri());

        $I->assertEquals($expectedRedirect->getId(), $redirect->getId());
    }

    public function testGetByPathThrowNotFoundIfPathUndefined(IntegrationalTester $I): void
    {
        $I->expectThrowable(
            RedirectNotFoundException::class,
            function () {
                $this->repository->getByUri(new Uri('/undefined-path'));
            }
        );
    }
}
