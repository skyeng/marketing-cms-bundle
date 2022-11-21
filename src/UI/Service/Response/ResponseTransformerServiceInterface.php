<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Service\Response;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

interface ResponseTransformerServiceInterface
{
    public function transform(ResponseInterface $response): Response;
}
