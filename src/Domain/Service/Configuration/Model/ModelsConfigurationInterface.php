<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model;

use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\ModelConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Exception\NotFoundModelConfigException;

interface ModelsConfigurationInterface
{
    /**
     * @return ModelConfig[]
     */
    public function getModelConfigs(): array;

    /**
     * @throws NotFoundModelConfigException
     */
    public function getModelConfig(string $name): ModelConfig;

    public function hasModelConfig(string $name): bool;
}
