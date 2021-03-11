<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Page;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageSeoData;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageSeoDataRepository\Exception\PageSeoDataNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageSeoDataRepository\Exception\PageSeoDataRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageSeoDataRepository\PageSeoDataRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

class PageSeoDataRepository extends ServiceEntityRepository implements PageSeoDataRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageSeoData::class);
    }

    public function getByPage(Page $page): PageSeoData
    {
        try {
            $seoData = $this->findOneBy(['page' => $page]);

            if (!$seoData) {
                throw new PageSeoDataNotFoundException();
            }

            return $seoData;
        } catch (PageSeoDataNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new PageSeoDataRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
