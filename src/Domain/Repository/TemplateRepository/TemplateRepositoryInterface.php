<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\Exception\TemplateNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\Exception\TemplateRepositoryException;

interface TemplateRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return Template[]
     *
     * @throws TemplateRepositoryException
     */
    public function getAll(): array;

    /**
     * @throws TemplateRepositoryException
     * @throws TemplateNotFoundException
     */
    public function getById(string $id, bool $withComponents = false): Template;

    /**
     * @param string[] $ids
     *
     * @return Template[]
     *
     * @throws TemplateRepositoryException
     */
    public function getByIds(array $ids): array;

    /**
     * @throws TemplateRepositoryException
     */
    public function save(Template $template): void;
}
