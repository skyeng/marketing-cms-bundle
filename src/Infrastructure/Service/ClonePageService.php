<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Skyeng\MarketingCmsBundle\Domain\Entity\Page;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageCustomMetaTag;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageOpenGraphData;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageSeoData;
use Skyeng\MarketingCmsBundle\Domain\Entity\Resource;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ResourceType;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Uri;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageComponentRepository\PageComponentRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageCustomMetaTagRepository\PageCustomMetaTagRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageRepository\PageRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\ResourceRepository\ResourceRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\ClonePageService\ClonePageServiceInterface;
use Skyeng\MarketingCmsBundle\Domain\Entity\Resource as ResourceEntity;
use Skyeng\MarketingCmsBundle\Domain\Service\ClonePageService\Exception\ClonePageServiceException;
use Throwable;

class ClonePageService implements ClonePageServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var PageRepositoryInterface
     */
    private $repository;

    /**
     * @var ResourceRepositoryInterface
     */
    private $resourceRepository;

    /**
     * @var PageComponentRepositoryInterface
     */
    private $pageComponentRepository;

    /**
     * @var PageCustomMetaTagRepositoryInterface
     */
    private $pageCustomMetaTagRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        EntityManagerInterface $em,
        PageRepositoryInterface $repository,
        ResourceRepositoryInterface $resourceRepository,
        PageComponentRepositoryInterface $pageComponentRepository,
        PageCustomMetaTagRepositoryInterface $pageCustomMetaTagRepository,
        LoggerInterface $logger
    ) {
        $this->em = $em;
        $this->repository = $repository;
        $this->resourceRepository = $resourceRepository;
        $this->pageComponentRepository = $pageComponentRepository;
        $this->pageCustomMetaTagRepository = $pageCustomMetaTagRepository;
        $this->logger = $logger;
    }

    public function clone(Page $page): Page
    {
        try {
            $this->em->beginTransaction();

            $resource = $this->cloneResource($page->getResource());

            $newPage = new Page(
                $this->repository->getNextIdentity(),
                $resource,
                '(clone ' . time() . ')' . $page->getTitle(),
                $page->getPublishedAt(),
                $page->getCreatedAt(),
            );

            $this->clonePageComponentsToNewPage($page, $newPage);
            $this->clonePageCustomMetaTagsToNewPage($page, $newPage);
            $this->clonePageSeoDataToNewPage($page, $newPage);
            $this->clonePageOpenGraphDataToNewPage($page, $newPage);

            $this->repository->save($newPage);

            $this->em->commit();
        } catch (Throwable $e) {
            $this->em->rollback();
            $this->logger->error('Произошла ошибка во время клонирования страницы', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
            throw new ClonePageServiceException($e->getMessage(), $e->getCode(), $e);
        }

        return $newPage;
    }

    private function cloneResource(ResourceEntity $resource): ResourceEntity
    {
        return new Resource(
            $this->resourceRepository->getNextIdentity(),
            new Uri('/' . 'clone-' . time() . '-' . $resource->getUri()->getPathname()),
            new ResourceType(ResourceType::PAGE_TYPE)
        );
    }

    private function clonePageComponentsToNewPage(Page $page, Page $newPage): void
    {
        foreach ($page->getComponents() as $component) {
            $newComponent = new PageComponent(
                $this->pageComponentRepository->getNextIdentity(),
                $newPage,
                $component->getName(),
                $component->getData(),
                $component->getOrder(),
            );
            $newComponent->setIsPublished($component->isPublished());
            $newPage->addComponent($newComponent);
        }
    }

    private function clonePageCustomMetaTagsToNewPage(Page $page, Page $newPage): void
    {
        foreach ($page->getCustomMetaTags() as $metaTag) {
            $newMetaTag = new PageCustomMetaTag(
                $this->pageCustomMetaTagRepository->getNextIdentity(),
                $newPage,
                $metaTag->getName(),
                $metaTag->getProperty(),
                $metaTag->getContent(),
            );
            $newPage->addCustomMetaTag($newMetaTag);
        }
    }

    private function clonePageSeoDataToNewPage(Page $page, Page $newPage): void
    {
        if ($page->getPageSeoData() === null) {
            return;
        }

        $seoData = new PageSeoData(
            $newPage,
            $page->getPageSeoData()->getTitle(),
            $page->getPageSeoData()->getDescription(),
            $page->getPageSeoData()->getKeywords(),
            $page->getPageSeoData()->isNoFollow(),
            $page->getPageSeoData()->isNoIndex(),
        );
        $newPage->setPageSeoData($seoData);
    }

    private function clonePageOpenGraphDataToNewPage(Page $page, Page $newPage): void
    {
        if ($page->getPageOpenGraphData() === null) {
            return;
        }

        $openGraphData = new PageOpenGraphData(
            $newPage,
            $page->getPageOpenGraphData()->getType(),
            $page->getPageOpenGraphData()->getUrl(),
            $page->getPageOpenGraphData()->getTitle(),
            $page->getPageOpenGraphData()->getImage(),
            $page->getPageOpenGraphData()->getDescription(),
        );
        $newPage->setPageOpenGraphData($openGraphData);
    }
}
