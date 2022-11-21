<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Security\Authorization;

use Skyeng\MarketingCmsBundle\Domain\Service\Security\AuthorizationServiceInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Security\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class AuthorizationService implements AuthorizationServiceInterface
{
    public function __construct(private AuthorizationCheckerInterface $authorizationChecker)
    {
    }

    public function denyAccessUnlessGranted(array $attributes): void
    {
        foreach ($attributes as $attribute) {
            if ($this->authorizationChecker->isGranted($attribute)) {
                return;
            }
        }

        throw new AccessDeniedException();
    }
}
