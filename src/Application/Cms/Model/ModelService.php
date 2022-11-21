<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Model;

use Skyeng\MarketingCmsBundle\Application\Cms\Model\Assembler\CloneModelResultAssembler;
use Skyeng\MarketingCmsBundle\Application\Cms\Model\Assembler\ModelAssemblerInterface;
use Skyeng\MarketingCmsBundle\Application\Cms\Model\Dto\CloneModelRequestDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Model\Dto\CloneModelResultDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Model\Dto\ModelRequest;
use Skyeng\MarketingCmsBundle\Application\Cms\Model\Dto\ModelsRequest;
use Skyeng\MarketingCmsBundle\Application\Cms\Model\Dto\PaginatedModelsResponse;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Domain\Factory\Model\ModelFactoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\ModelRepository\ModelRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Validator\Exception\ModelNotValidException;
use Skyeng\MarketingCmsBundle\Domain\Service\Validator\ModelValidatorInterface;

class ModelService
{
    public function __construct(
        private ModelAssemblerInterface $assembler,
        private ModelRepositoryInterface $modelRepository,
        private ModelFactoryInterface $modelFactory,
        private CloneModelResultAssembler $cloneModelResultAssembler,
        private ModelValidatorInterface $modelValidator
    ) {
    }

    /**
     * @return mixed[]
     */
    public function getModel(ModelRequest $modelRequest): array
    {
        $model = $this->modelRepository->getById($modelRequest->id);

        return $this->assembler->assemble($model, $modelRequest->locale);
    }

    public function filterModels(ModelsRequest $modelsRequest): PaginatedModelsResponse
    {
        $paginator = $this->modelRepository->filter(
            $modelsRequest->modelName,
            $modelsRequest->filters,
            $modelsRequest->sorts,
            $modelsRequest->page,
            $modelsRequest->perPage,
            $modelsRequest->locale,
        );

        return new PaginatedModelsResponse(
            array_map(fn (Model $model) => $this->assembler->assemble($model, $modelsRequest->locale), $paginator->getResults()),
            $paginator->getNumResults(),
            $paginator->hasNextPage(),
            $paginator->getNextPage(),
            $paginator->hasPreviousPage(),
            $paginator->getPreviousPage(),
        );
    }

    public function cloneModel(CloneModelRequestDto $dto): CloneModelResultDto
    {
        $model = $this->modelRepository->getById($dto->id);

        $newModel = $this->modelFactory->clone($model);

        try {
            $this->modelValidator->validate($newModel);
        } catch (ModelNotValidException $e) {
            throw new ValidationException(message: 'Clone service created invalid Model', errors: $e->getErrors());
        }

        $this->modelRepository->save($newModel);

        return $this->cloneModelResultAssembler->assemble($newModel);
    }
}
