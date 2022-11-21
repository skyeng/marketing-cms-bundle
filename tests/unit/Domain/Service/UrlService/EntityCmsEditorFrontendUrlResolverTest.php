<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Unit\Domain\Service\UrlService;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Editor\EditorConfigurationInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Locale\LocaleResolverInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\UrlService\CurrentUrlProviderServiceInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\UrlService\EntityCmsEditorFrontendUrlResolver;

class EntityCmsEditorFrontendUrlResolverTest extends Unit
{
    public function testCreateCorrectUrl(): void
    {
        /** @var EditorConfigurationInterface $editorConfiguration */
        $editorConfiguration = $this->makeEmpty(EditorConfigurationInterface::class, [
            'getShowEditorLink' => true,
        ]);

        /** @var CurrentUrlProviderServiceInterface $currentUrlProviderService */
        $currentUrlProviderService = $this->makeEmpty(CurrentUrlProviderServiceInterface::class, [
            'getSchemeAndHost' => 'https://skyeng.ru',
            'getUri' => 'https://frontface-backend.skyeng.ru/admin?crudAction=index',
        ]);

        /** @var LocaleResolverInterface $localeResolver */
        $localeResolver = $this->makeEmpty(LocaleResolverInterface::class, [
            'getCurrentLocale' => 'ru',
        ]);

        $urlResolver = new EntityCmsEditorFrontendUrlResolver(
            $editorConfiguration,
            $currentUrlProviderService,
            $localeResolver,
        );

        $id = new Id(Uuid::uuid4()->toString());

        $this->assertSame(
            'https://skyeng.ru/admin/cms-editor/'.$id->getValue().'?locale=ru&referrer=https%3A%2F%2Ffrontface-backend.skyeng.ru%2Fadmin%3FcrudAction%3Dindex',
            $urlResolver->createUrl($id),
        );
    }

    public function testCreateEmptyUrl(): void
    {
        /** @var EditorConfigurationInterface $editorConfiguration */
        $editorConfiguration = $this->makeEmpty(EditorConfigurationInterface::class, [
            'getShowEditorLink' => false,
        ]);

        /** @var CurrentUrlProviderServiceInterface $currentUrlProviderService */
        $currentUrlProviderService = $this->makeEmpty(CurrentUrlProviderServiceInterface::class, [
            'getSchemeAndHost' => Expected::never(),
            'getUri' => Expected::never(),
        ]);

        /** @var LocaleResolverInterface $localeResolver */
        $localeResolver = $this->makeEmpty(LocaleResolverInterface::class, [
            'getCurrentLocale' => Expected::never(),
        ]);

        $urlResolver = new EntityCmsEditorFrontendUrlResolver(
            $editorConfiguration,
            $currentUrlProviderService,
            $localeResolver,
        );

        $id = new Id(Uuid::uuid4()->toString());

        $this->assertSame('', $urlResolver->createUrl($id));
    }
}
