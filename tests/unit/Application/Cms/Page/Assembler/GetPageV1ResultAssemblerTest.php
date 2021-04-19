<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Unit\Application\Cms\Page\Assembler;

use Codeception\Test\Unit;
use Psr\Log\NullLogger;
use Skyeng\MarketingCmsBundle\Application\Cms\Page\Assembler\GetPageV1ResultAssembler;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\PageComponentName;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\TemplateRepositoryInterface;
use Skyeng\MarketingCmsBundle\Tests\Builder\PageBuilder;
use Skyeng\MarketingCmsBundle\Tests\Builder\PageComponentBuilder;
use Skyeng\MarketingCmsBundle\Tests\Builder\TemplateBuilder;
use Skyeng\MarketingCmsBundle\Tests\Builder\TemplateComponentBuilder;

class GetPageV1ResultAssemblerTest extends Unit
{
    public function testSuccessAssembleWithComponents(): void
    {
        $templateComponentBuilder = TemplateComponentBuilder::templateComponent();
        $templateComponent = $templateComponentBuilder->withOrder(3)->withName(new PageComponentName('template-3'));
        $templateComponentSecond = $templateComponentBuilder->withOrder(1)->withName(new PageComponentName('template-1'));
        $templateComponentThird = $templateComponentBuilder->withOrder(2)->withPublished(false)->withName(new PageComponentName('template-2'));

        $template = TemplateBuilder::template()
            ->withComponents([$templateComponent, $templateComponentSecond, $templateComponentThird])
            ->build();

        $pageComponentBuilder = PageComponentBuilder::pageComponent();
        $pageComponent = $pageComponentBuilder->withOrder(1)->withName(new PageComponentName('component-1'));
        $pageComponentSecond = $pageComponentBuilder->withOrder(2)->withData(['template' => $template->getId()->getValue()])->withName(new PageComponentName('component-2'));
        $pageComponentThird = $pageComponentBuilder->withOrder(3)->withPublished(false)->withName(new PageComponentName('component-3'));

        $page = PageBuilder::page()->withComponents([$pageComponent, $pageComponentSecond, $pageComponentThird])->build();

        $templateRepository = $this->makeEmpty(TemplateRepositoryInterface::class, [
            'getById' => $template,
        ]);

        $assembler = new GetPageV1ResultAssembler(new NullLogger(), $templateRepository);
        $result = $assembler->assemble($page);

        $this->assertNotNull($result->result);
        $this->assertSame($page->getTitle(), $result->result->title);
        $this->assertCount(3, $result->result->components);

        $expectedComponents = [
            'component-1',
            'template-1',
            'template-3',
        ];

        foreach ($result->result->components as $key => $componentDto) {
            $this->assertSame($expectedComponents[$key], $componentDto->name);
            $this->assertSame($key + 1, $componentDto->order);
        }
    }
}
