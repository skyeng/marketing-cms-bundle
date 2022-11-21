<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\UrlService;

use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\ModelConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\UrlService\Exception\NotPossibleToCreateUrlException;
use Skyeng\MarketingCmsBundle\Domain\Service\UrlService\Exception\UnexpectedVariableInPatternUrlException;

interface ModelFrontendUrlResolverInterface
{
    /**
     * @throws UnexpectedVariableInPatternUrlException
     * @throws NotPossibleToCreateUrlException
     */
    public function createUrl(Model $model): string;

    public function canCreateUrl(ModelConfig $modelConfig): bool;
}
