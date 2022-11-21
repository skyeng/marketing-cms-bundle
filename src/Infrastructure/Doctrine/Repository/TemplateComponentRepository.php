<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\Domain\Entity\TemplateComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateComponentRepository\Exception\TemplateComponentRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateComponentRepository\TemplateComponentRepositoryInterface;

class TemplateComponentRepository extends ServiceEntityRepository implements TemplateComponentRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplateComponent::class);
    }

    public function getNextIdentity(): Id
    {
        $uuid = Uuid::uuid4();

        return new Id($uuid->toString());
    }

    /**
     * {@inheritDoc}
     */
    public function getByTemplate(Template $template): array
    {
        try {
            /** @var TemplateComponent[] $templateComponents */
            $templateComponents = $this->findBy(['template' => $template]);
        } catch (Exception $e) {
            throw new TemplateComponentRepositoryException($e->getMessage(), $e->getCode(), $e);
        }

        return $templateComponents;
    }

    public function save(TemplateComponent ...$templateComponents): void
    {
        $em = $this->getEntityManager();

        try {
            $em->beginTransaction();

            foreach ($templateComponents as $templateComponent) {
                $em->persist($templateComponent);
            }
            $em->flush();

            $em->commit();
        } catch (Exception $e) {
            $em->rollback();
            throw new TemplateComponentRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function remove(TemplateComponent ...$templateComponents): void
    {
        $em = $this->getEntityManager();

        try {
            $em->beginTransaction();

            foreach ($templateComponents as $templateComponent) {
                $em->remove($templateComponent);
            }
            $em->flush();

            $em->commit();
        } catch (Exception $e) {
            $em->rollback();
            throw new TemplateComponentRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
