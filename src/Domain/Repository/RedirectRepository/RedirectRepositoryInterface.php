<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Repository\RedirectRepository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Redirect;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Uri;
use Skyeng\MarketingCmsBundle\Domain\Repository\RedirectRepository\Exception\RedirectNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\RedirectRepository\Exception\RedirectRepositoryException;

interface RedirectRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return Redirect[]
     *
     * @throws RedirectRepositoryException
     */
    public function getAll(): array;

    /**
     * @throws RedirectRepositoryException
     * @throws RedirectNotFoundException
     */
    public function getByUri(Uri $uri): Redirect;
}
