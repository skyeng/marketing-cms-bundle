<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\Exception\TemplateNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\Exception\TemplateRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\TemplateRepositoryInterface;

class TemplateRepository extends ServiceEntityRepository implements TemplateRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Template::class);
    }

    public function getNextIdentity(): Id
    {
        $uuid = Uuid::uuid4();
        return new Id($uuid->toString());
    }

    public function getAll(): array
    {
        try {
            return $this->findBy([]);
        } catch (Exception $e) {
            throw new TemplateRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getById(string $id): Template
    {
        try {
            $template = $this->findOneBy(['id' => $id]);

            if (!$template) {
                throw new TemplateNotFoundException();
            }

            return $template;
        } catch (TemplateNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new TemplateRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getByIds(array $ids): array
    {
        try {
            return $this->findBy(['id' => $ids]);
        } catch (Exception $e) {
            throw new TemplateRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
