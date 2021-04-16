<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\Exception\TemplateNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\Exception\TemplateRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

interface TemplateRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return Template[]
     * @throws TemplateRepositoryException
     */
    public function getAll(): array;

    /**
     * @return Template
     * @throws TemplateRepositoryException
     * @throws TemplateNotFoundException
     */
    public function getById(string $id): Template;

    /**
     * @param string[] $ids
     * @return Template[]
     * @throws TemplateRepositoryException
     */
    public function getByIds(array $ids): array;
}
