<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms;

use Generator;
use Psr\Log\LoggerInterface;
use Skyeng\MarketingCmsBundle\Domain\Entity\AbstractComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\Component;
use Skyeng\MarketingCmsBundle\Domain\Entity\Field;
use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\Domain\Entity\TemplateComponent;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\TemplateRepositoryInterface;

class PublishedComponentsGenerator
{
    public function __construct(
        private TemplateRepositoryInterface $templateRepository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @return Generator|AbstractComponent[]
     */
    public function getComponents(Field $field): Generator
    {
        $components = $field->getComponents()->toArray();
        $templates = $this->getTemplatesFromComponents($field);
        usort(
            $components,
            static fn (Component $first, Component $second): int => $first->getOrder() <=> $second->getOrder(),
        );

        /** @var Component $component */
        foreach ($components as $component) {
            if (!$component->isPublished()) {
                continue;
            }

            if (!$component->getName()->isTemplateName()) {
                yield $component;
                continue;
            }

            if (!array_key_exists('template', $component->getData())) {
                $this->logger->warning(
                    'Template component has no template in data',
                    ['templateComponentId' => $component->getId()->getValue()]
                );
                continue;
            }

            $componentKey = $component->getData()['template'] ?? null;

            if ($componentKey === null || $componentKey === '') {
                $this->logger->warning(
                    'Template component has null template in data',
                    ['templateComponentId' => $component->getId()->getValue()]
                );
                continue;
            }

            $template = $templates[$componentKey] ?? null;

            if (!$template instanceof Template) {
                continue;
            }

            /** @var TemplateComponent $templateComponent */
            foreach ($template->getComponents() as $templateComponent) {
                if (!$templateComponent->isPublished()) {
                    continue;
                }

                yield $templateComponent;
            }
        }
    }

    /**
     * @return array<string, Template>
     */
    private function getTemplatesFromComponents(Field $field): array
    {
        $componentTemplateIds = [];

        /** @var Component $component */
        foreach ($field->getComponents() as $component) {
            if (!$component->isPublished()) {
                continue;
            }

            if (!$component->getName()->isTemplateName()) {
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
