<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\MediaCatalog;
use Skyeng\MarketingCmsBundle\Domain\Entity\MediaFile;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Repository\MediaFileRepository\Exception\MediaFileRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Repository\MediaFileRepository\MediaFileRepositoryInterface;

class MediaFileRepository extends ServiceEntityRepository implements MediaFileRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaFile::class);
    }

    public function getNextIdentity(): Id
    {
        $uuid = Uuid::uuid4();

        return new Id($uuid->toString());
    }

    /**
     * {@inheritDoc}
     */
    public function getByCatalog(MediaCatalog $catalog): array
    {
        try {
            /** @var MediaFile[] $mediaFiles */
            $mediaFiles = $this->findBy(['catalog' => $catalog]);
        } catch (Exception $e) {
            throw new MediaFileRepositoryException($e->getMessage(), $e->getCode(), $e);
        }

        return $mediaFiles;
    }

    /**
     * {@inheritDoc}
     */
    public function save(MediaFile ...$mediaFiles): void
    {
        $em = $this->getEntityManager();

        try {
            $em->beginTransaction();

            foreach ($mediaFiles as $mediaFile) {
                $em->persist($mediaFile);
            }

            $em->flush();

            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw new MediaFileRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function remove(MediaFile ...$mediaFiles): void
    {
        $em = $this->getEntityManager();

        try {
            $em->beginTransaction();

            foreach ($mediaFiles as $mediaFile) {
                $em->remove($mediaFile);
            }
            $em->flush();

            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw new MediaFileRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getByIds(array $ids): array
    {
        try {
            /** @var MediaFile[] $mediaFiles */
            $mediaFiles = $this->findBy(['id' => $ids]);
        } catch (Exception $e) {
            throw new MediaFileRepositoryException($e->getMessage(), $e->getCode(), $e);
        }

        return $mediaFiles;
    }
}
