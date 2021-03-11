<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Page;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageOpenGraphData;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageOpenGraphDataRepository\Exception\PageOpenGraphDataNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageOpenGraphDataRepository\Exception\PageOpenGraphDataRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageOpenGraphDataRepository\PageOpenGraphDataRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

class PageOpenGraphDataRepository extends ServiceEntityRepository implements PageOpenGraphDataRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageOpenGraphData::class);
    }

    public function getByPage(Page $page): PageOpenGraphData
    {
        try {
            $openGraphData = $this->findOneBy(['page' => $page]);

            if (!$openGraphData) {
                throw new PageOpenGraphDataNotFoundException();
            }

            return $openGraphData;
        } catch (PageOpenGraphDataNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new PageOpenGraphDataRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
