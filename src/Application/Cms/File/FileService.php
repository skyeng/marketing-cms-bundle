<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\File;

use Skyeng\MarketingCmsBundle\Application\Cms\File\Dto\GetFileV1RequestDto;
use Skyeng\MarketingCmsBundle\Application\Cms\File\Dto\GetFileV1ResultDto;
use Skyeng\MarketingCmsBundle\Application\Cms\File\Assembler\GetFileV1ResultAssemblerInterface;
use Skyeng\MarketingCmsBundle\Application\Cms\File\Exception\FileRedirectException;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Uri;
use Skyeng\MarketingCmsBundle\Domain\Repository\FileRepository\Exception\FileNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\FileRepository\Exception\FileRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Repository\FileRepository\FileRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\RedirectRepository\Exception\RedirectNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\RedirectRepository\Exception\RedirectRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Repository\RedirectRepository\RedirectRepositoryInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

class FileService
{
    use LoggerAwareTrait;

    /**
     * @var GetFileV1ResultAssemblerInterface
     */
    private $getFileV1ResultAssembler;

    /**
     * @var FileRepositoryInterface
     */
    private $fileRepository;

    /**
     * @var RedirectRepositoryInterface
     */
    private $redirectRepository;

    public function __construct(
        GetFileV1ResultAssemblerInterface $getFileV1ResultAssembler,
        FileRepositoryInterface $fileRepository,
        RedirectRepositoryInterface $redirectRepository,
        LoggerInterface $logger
    ) {
        $this->getFileV1ResultAssembler = $getFileV1ResultAssembler;
        $this->fileRepository = $fileRepository;
        $this->redirectRepository = $redirectRepository;
        $this->logger = $logger;
    }

    /**
     * @param GetFileV1RequestDto $dto
     * @return GetFileV1ResultDto
     * @throws FileRedirectException
     * @throws FileNotFoundException
     * @throws FileRepositoryException
     */
    public function getFile(GetFileV1RequestDto $dto): GetFileV1ResultDto
    {
        try {
            $redirect = $this->redirectRepository->getByUri(new Uri($dto->uri));
            throw new FileRedirectException($redirect->getTargetUrl(), $redirect->getHttpCode());
        } catch (RedirectNotFoundException $e) {
            $this->logger->debug('Redirect for file not found', ['uri' => $dto->uri]);
        } catch (RedirectRepositoryException $e) {
            $this->logger->warning(
                'Something went wrong when try get redirect for file uri',
                ['uri' => $dto->uri, 'errorMessage' => $e->getMessage()]
            );
        }

        $file = $this->fileRepository->getByUri($dto->uri);
        return $this->getFileV1ResultAssembler->assemble($file);
    }
}
