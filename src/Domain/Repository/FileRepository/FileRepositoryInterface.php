<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Repository\FileRepository;

use Skyeng\MarketingCmsBundle\Domain\Entity\File;
use Skyeng\MarketingCmsBundle\Domain\Repository\FileRepository\Exception\FileNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\FileRepository\Exception\FileRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

interface FileRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return File[]
     * @throws FileRepositoryException
     */
    public function getAll(): array;

    /**
     * @param string $uri
     * @return File
     * @throws FileRepositoryException
     * @throws FileNotFoundException
     */
    public function getByUri(string $uri): File;
}
