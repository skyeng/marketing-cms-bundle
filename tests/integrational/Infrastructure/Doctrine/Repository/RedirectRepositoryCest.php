<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Integrational\Infrastructure\Doctrine\Repository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Redirect;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Uri;
use Skyeng\MarketingCmsBundle\Domain\Repository\RedirectRepository\Exception\RedirectNotFoundException;
use Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository\RedirectRepository;
use Skyeng\MarketingCmsBundle\Tests\Builder\RedirectBuilder;
use Skyeng\MarketingCmsBundle\Tests\IntegrationalTester;

class RedirectRepositoryCest
{
    public function testGetAll(IntegrationalTester $I): void
    {
        $repository = $this->getRedirectRepository($I);

        $redirect1 = RedirectBuilder::redirect()->build();
        $redirect2 = RedirectBuilder::redirect()->build();
        $repository->save($redirect1);
        $repository->save($redirect2);

        $redirects = $repository->getAll();

        $I->assertCount(2, $redirects);
        $I->assertEquals($redirect1->getId(), $redirects[0]->getId());
        $I->assertEquals($redirect2->getId(), $redirects[1]->getId());
    }

    public function testGetByPath(IntegrationalTester $I): void
    {
        $repository = $this->getRedirectRepository($I);

        $expectedRedirect = RedirectBuilder::redirect()->build();
        $repository->save($expectedRedirect);

        $redirect = $repository->getByUri($expectedRedirect->getResource()->getUri());

        $I->assertEquals($expectedRedirect->getId(), $redirect->getId());
    }

    public function testGetByPathThrowNotFoundIfPathUndefined(IntegrationalTester $I): void
    {
        $I->expectThrowable(
            RedirectNotFoundException::class,
            fn (): Redirect => $this->getRedirectRepository($I)->getByUri(new Uri('/undefined-path')),
        );
    }

    private function getRedirectRepository(IntegrationalTester $I): RedirectRepository
    {
        return $I->getService(RedirectRepository::class);
    }
}
