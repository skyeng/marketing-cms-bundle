<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Page\Assembler;

use Skyeng\MarketingCmsBundle\Application\Cms\Page\Dto\GetPageV1PageComponentDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Page\Dto\GetPageV1PageMetaTagDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Page\Dto\GetPageV1ResultDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Page\Dto\PageDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\Page;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageCustomMetaTag;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

class GetPageV1ResultAssembler implements GetPageV1ResultAssemblerInterface
{
    use LoggerAwareTrait;

    private const DEFAULT_LAYOUT = 'skysmart-base';

    private const RESERVED_NAMES = [
        'title',
        'description',
        'keywords',
        'robots',
    ];

    private const RESERVED_PROPERTIES = [
        'og:url',
        'og:type',
        'og:title',
        'og:description',
        'og:image',
    ];

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function assemble(Page $page): GetPageV1ResultDto
    {
        $result = new GetPageV1ResultDto();
        $result->result = new PageDto(
            $page->getTitle(),
            $page->getResource()->getUri()->getValue(),
            self::DEFAULT_LAYOUT,
            $this->createMetaTagsDtoArray($page),
            $this->createComponentsDtoArray($page),
        );

        return $result;
    }

    private function createMetaTagsDtoArray(Page $page): array
    {
        $tags = [];

        $this->fillPageSeoMetaTags($tags, $page);
        $this->fillPageOpenGraphMetaTags($tags, $page);

        foreach ($page->getCustomMetaTags() as $customMetaTag) {
            /** @var PageCustomMetaTag $customMetaTag */
            if (in_array($customMetaTag->getName(), self::RESERVED_NAMES, true)) {
                continue;
            }

            if (in_array($customMetaTag->getProperty(), self::RESERVED_PROPERTIES, true)) {
                continue;
            }

            $tags[] = new GetPageV1PageMetaTagDto(
                $customMetaTag->getName(),
                $customMetaTag->getProperty(),
                $customMetaTag->getContent(),
            );
        }

        return $tags;
    }

    private function createComponentsDtoArray(Page $page): array
    {
        $components = [];

        foreach ($page->getComponents() as $component) {
            /** @var PageComponent $component */
            if (!$component->isPublished()) {
                continue;
            }

            $components[] = new GetPageV1PageComponentDto(
                $component->getName()->getValue(),
                $component->getData(),
                $component->getOrder(),
            );
        }

        return $components;
    }

    private function fillPageSeoMetaTags(array &$tags, Page $page): void
    {
        $seo = $page->getPageSeoData();

        if (!$seo) {
            $this->logger->warning('Page has no page seo data', ['pageId' => $page->getId()]);
            return;
        }

        $tags[] = new GetPageV1PageMetaTagDto('title', null, $seo->getTitle());
        $tags[] = new GetPageV1PageMetaTagDto('description', null, $seo->getDescription());
        $tags[] = new GetPageV1PageMetaTagDto('keywords', null, $seo->getKeywords());

        $robotsContent = [];

        if ($seo->isNoFollow()) {
            $robotsContent[] = 'nofollow';
        }

        if ($seo->isNoIndex()) {
            $robotsContent[] = 'noindex';
        }

        $tags[] = new GetPageV1PageMetaTagDto('robots', null, implode(', ', $robotsContent));
    }

    private function fillPageOpenGraphMetaTags(array &$tags, Page $page): void
    {
        $openGraph = $page->getPageOpenGraphData();

        if (!$openGraph) {
            $this->logger->warning('Page has no open graph data', ['pageId' => $page->getId()]);
            return;
        }

        $tags[] = new GetPageV1PageMetaTagDto(null, 'og:url', $openGraph->getUrl());
        $tags[] = new GetPageV1PageMetaTagDto(null, 'og:type', $openGraph->getType());
        $tags[] = new GetPageV1PageMetaTagDto(null, 'og:title', $openGraph->getTitle());
        $tags[] = new GetPageV1PageMetaTagDto(null, 'og:description', $openGraph->getDescription());
        $tags[] = new GetPageV1PageMetaTagDto(null, 'og:image', $openGraph->getImage());
    }
}
