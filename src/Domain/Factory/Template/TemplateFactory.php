<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Factory\Template;

use DateTimeImmutable;
use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\Domain\Factory\Template\Exception\TemplateCannotBeClonedException;
use Skyeng\MarketingCmsBundle\Domain\Factory\TemplateComponent\TemplateComponentFactoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\TemplateRepositoryInterface;
use Throwable;

class TemplateFactory implements TemplateFactoryInterface
{
    public function __construct(
        private TemplateRepositoryInterface $templateRepository,
        private TemplateComponentFactoryInterface $templateComponentFactory,
    ) {
    }

    public function create(string $name): Template
    {
        return new Template(
            $this->templateRepository->getNextIdentity(),
            $name,
            new DateTimeImmutable('now'),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function clone(Template $template): Template
    {
        try {
            $newTemplate = $this->create(sprintf('(clone %s)%s', time(), $template->getName()));

            foreach ($template->getComponents() as $component) {
                $newComponent = $this->templateComponentFactory->create(
                    $newTemplate,
                    $component->getName(),
                    $component->getData(),
                    $component->getOrder(),
                    $component->isPublished(),
                );

                $newTemplate->addComponent($newComponent);
            }
        } catch (Throwable $e) {
            throw new TemplateCannotBeClonedException($e->getMessage(), $e->getCode(), $e);
        }

        return $newTemplate;
    }
}
