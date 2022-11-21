<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\MediaFile;

use Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Assembler\AddMediaFile\AddMediaFileV1ResultAssemblerInterface;
use Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Dto\AddMediaFile\AddMediaFileV1RequestDto;
use Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Dto\AddMediaFile\AddMediaFileV1ResultDto;
use Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Dto\RemoveMediaFiles\RemoveMediaFilesV1RequestDto;
use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;
use Skyeng\MarketingCmsBundle\Domain\Entity\MediaFile;
use Skyeng\MarketingCmsBundle\Domain\Factory\MediaFile\MediaFileFactoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\MediaFileRepository\MediaFileRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\MediaCatalog\MediaCatalogResolverInterface;

class MediaFileService
{
    private const CMS_EDITOR_FILE_NAME = 'Cms Editor File';

    public function __construct(
        private AddMediaFileV1ResultAssemblerInterface $addMediaFileV1ResultAssembler,
        private MediaCatalogResolverInterface $mediaCatalogResolver,
        private MediaFileFactoryInterface $mediaFileFactory,
        private MediaFileRepositoryInterface $mediaFileRepository
    ) {
    }

    public function addMediaFile(AddMediaFileV1RequestDto $dto): AddMediaFileV1ResultDto
    {
        $mediaCatalog = $this->mediaCatalogResolver->getCatalogForEditor();

        $mediaFile = $this->mediaFileFactory->create(
            $mediaCatalog,
            self::CMS_EDITOR_FILE_NAME,
            $dto->file,
        );

        $this->mediaFileRepository->save($mediaFile);

        return $this->addMediaFileV1ResultAssembler->assemble($mediaFile);
    }

    /**
     * @throws ValidationException
     */
    public function removeFiles(RemoveMediaFilesV1RequestDto $requestDto): void
    {
        $mediaFiles = $this->mediaFileRepository->getByIds($requestDto->ids);

        $this->validOrThrowException($requestDto, $mediaFiles);

        if ($mediaFiles !== []) {
            $this->mediaFileRepository->remove(...$mediaFiles);
        }
    }

    /**
     * @param MediaFile[] $foundMediaFiles
     */
    private function validOrThrowException(RemoveMediaFilesV1RequestDto $requestDto, array $foundMediaFiles): void
    {
        $notFoundMediaFilesIds = array_diff(
            $requestDto->ids,
            array_map(static fn (MediaFile $mediaFile): string => $mediaFile->getId()->getValue(), $foundMediaFiles)
        );

        if (!empty($notFoundMediaFilesIds)) {
            throw new ValidationException('Validation error', 400, null, ['ids' => sprintf('MediaFiles with ids «%s» not found.', implode(', ', $notFoundMediaFilesIds))]);
        }
    }
}
