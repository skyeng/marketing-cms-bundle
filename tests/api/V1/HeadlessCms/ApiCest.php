<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Api\V1\HeadlessCms;

use Codeception\Util\HttpCode;
use Skyeng\MarketingCmsBundle\Tests\ApiTester;
use Skyeng\MarketingCmsBundle\Tests\DataFixtures\HeadlessCms\Crud\FieldFixtures as CrudFixtures;
use Skyeng\MarketingCmsBundle\Tests\DataFixtures\HeadlessCms\FilterSort\FieldFixtures as FilterSortFixtures;
use Skyeng\MarketingCmsBundle\Tests\DataFixtures\HeadlessCms\Localization\ComponentFixtures;
use Skyeng\MarketingCmsBundle\Tests\DataFixtures\HeadlessCms\Localization\FieldFixtures as LocalizedFieldFixtures;

class ApiCest
{
    public function show(ApiTester $I): void
    {
        $I->haveFixtures([CrudFixtures::class]);

        $I->sendGET('/api/v1/cms/get-model?id=e9569f15-c58d-490d-823f-068d032ab67c');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseContainsJson([
            'title' => 'article_title1',
            'url' => 'article_url1',
            'description' => 'article_description1',
            'content' => 'article_content1',
            'published' => true,
            'published_at' => '2021-10-23T23:31:00+0000',
        ]);
    }

    public function index(ApiTester $I): void
    {
        $I->haveFixtures([CrudFixtures::class]);

        $I->sendGET('/api/v1/cms/get-models?model=article&page=1&per_page=2');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseContainsJson([
            'data' => [
                ['title' => 'article_title1'],
                ['title' => 'article_title2'],
            ],
            'meta' => [
                'total' => 3,
                'current_page' => 1,
                'per_page' => 2,
            ],
        ]);

        $I->sendGET('/api/v1/cms/get-models?model=article&page=2&per_page=2');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseContainsJson([
            'data' => [
                ['title' => 'article_title3'],
            ],
            'meta' => [
                'total' => 3,
                'current_page' => 2,
                'per_page' => 2,
            ],
        ]);
    }

    public function localization(ApiTester $I): void
    {
        $I->haveFixtures([LocalizedFieldFixtures::class, ComponentFixtures::class]);

        $I->sendGET('/api/v1/cms/get-models?model=tag');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            [
                'title' => 'news',
                'components' => [
                    [
                        'data' => ['html' => 'en_html1'],
                    ],
                ],
            ],
        ]);
        $I->dontSeeResponseContainsJson([
            'html' => 'ru_html1',
        ]);

        $I->sendGET('/api/v1/cms/get-models?model=tag&locale=ru');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            [
                'title' => 'новости',
                'components' => [
                    'data' => ['html' => 'ru_html1'],
                ],
            ],
        ]);
        $I->dontSeeResponseContainsJson([
            'html' => 'en_html1',
        ]);
    }

    public function filterSort(ApiTester $I): void
    {
        $I->haveFixtures([FilterSortFixtures::class]);

        $I->sendGET('/api/v1/cms/get-models?model=webinar&filters[title]=webinar_title1&filters[complexity]=high&sorts[cost]=DESC');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseContainsJson([
            'data' => [
                ['title' => 'webinar_title1', 'cost' => 666],
                ['title' => 'webinar_title1', 'cost' => 555],
            ],
        ]);

        $I->dontSeeResponseContainsJson([
            ['title' => 'webinar_title3'],
        ]);
    }
}
