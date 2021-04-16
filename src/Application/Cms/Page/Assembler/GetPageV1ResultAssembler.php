<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Page\Assembler;

use Generator;
use Skyeng\MarketingCmsBundle\Application\Cms\Page\Dto\GetPageV1PageComponentDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Page\Dto\GetPageV1PageMetaTagDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Page\Dto\GetPageV1ResultDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Page\Dto\PageDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\AbstractComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\Page;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageCustomMetaTag;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Skyeng\MarketingCmsBundle\Domain\Entity\TemplateComponent;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\TemplateRepositoryInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\ComponentTypes\TemplateComponentType;

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

    /**
     * @var TemplateRepositoryInterface
     */
    private $templateRepository;

    public function __construct(LoggerInterface $logger, TemplateRepositoryInterface $templateRepository)
    {
        $this->logger = $logger;
        $this->templateRepository = $templateRepository;
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
        $order = 0;

        foreach ($this->getPublishedPageComponentsGenerator($page) as $component) {
            /** @var AbstractComponent $component */
            $components[] = new GetPageV1PageComponentDto(
                $component->getName()->getValue(),
                $component->getData(),
                ++$order,
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

    private function getPublishedPageComponentsGenerator(Page $page): Generator
    {
        $components = $page->getComponents()->toArray();
        $templates = $this->getTemplatesFromPageComponents($page);
        usort(
            $components,
            static function (PageComponent $first, PageComponent $second) {
                return $first->getOrder() > $second->getOrder();
            }
        );

        foreach ($components as $component) {
            /** @var PageComponent $component */
            if (!$component->isPublished()) {
                continue;
            }

            if ($component->getName()->getValue() !== TemplateComponentType::NAME) {
                yield $component;
            }

            if (!array_key_exists('template', $component->getData())){
                $this->logger->warning(
                    'Template component has no template in data',
                    ['templateComponentId' => $component->getId()->getValue()]
                );
                continue;
            }

            $template = $templates[$component->getData()['template']];

            if (!$template) {
                continue;
            }

            foreach ($template->getComponents() as $templateComponent) {
                /** @var TemplateComponent $component */
                if (!$templateComponent->isPublished()) {
                    continue;
                }

                yield $templateComponent;
            }
        }
    }

    private function getTemplatesFromPageComponents(Page $page): array
    {
        $componentTemplateIds = [];

        foreach ($page->getComponents() as $component) {
            /** @var PageComponent $component */
            if (!$component->isPublished()) {
                continue;
            }

            if ($component->getName()->getValue() !== TemplateComponentType::NAME) {
                continue;
            }

            if (!array_key_exists('template', $component->getData())) {
                continue;
            }

            $componentTemplateIds[] = $component->getData()['template'];
        }

        $templates = [];

        foreach ($this->templateRepository->getByIds($componentTemplateIds) as $template) {
            $templates[$template->getId()->getValue()] = $template;
        }

        return $templates;
    }

}
