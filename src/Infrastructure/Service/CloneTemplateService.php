<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Psr\Log\LoggerInterface;
use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\Domain\Entity\TemplateComponent;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateComponentRepository\TemplateComponentRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\TemplateRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\CloneTemplateService\CloneTemplateServiceInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\CloneTemplateService\Exception\CloneTemplateServiceException;

class CloneTemplateService implements CloneTemplateServiceInterface
{
    /**
     * @var TemplateRepositoryInterface
     */
    private $repository;

    /**
     * @var TemplateComponentRepositoryInterface
     */
    private $templateComponentRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        TemplateRepositoryInterface $templateRepository,
        TemplateComponentRepositoryInterface $templateComponentRepository,
        EntityManagerInterface $em,
        LoggerInterface $logger
    ) {
        $this->repository = $templateRepository;
        $this->templateComponentRepository = $templateComponentRepository;
        $this->em = $em;
        $this->logger = $logger;
    }

    public function clone(Template $template): Template
    {
        try {
            $this->em->beginTransaction();

            $newTemplate = new Template(
                $this->repository->getNextIdentity(),
                '(clone ' . time() . ')' . $template->getName(),
            );

            $this->cloneTemplateComponentsToNewTemplate($template, $newTemplate);

            $this->repository->save($newTemplate);
            $this->em->commit();
        } catch (ORMException | ORMInvalidArgumentException $e) {
            $this->em->rollback();
            $this->logger->error('Произошла ошибка во время клонирования готового компонента', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
            throw new CloneTemplateServiceException($e->getMessage(), $e->getCode(), $e);
        }

        return $newTemplate;
    }

    private function cloneTemplateComponentsToNewTemplate(Template $template, Template $newTemplate): void
    {
        foreach ($template->getComponents() as $component) {
            $newComponent = new TemplateComponent(
                $this->templateComponentRepository->getNextIdentity(),
                $newTemplate,
                $component->getName(),
                $component->getData(),
                $component->getOrder(),
            );
            $newComponent->setIsPublished($component->isPublished());
            $newTemplate->addComponent($newComponent);
        }
    }
}
