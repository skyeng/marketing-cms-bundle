<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Api\V1\Cms\TemplateComponent;

use Codeception\Exception\ModuleException;
use Codeception\Util\HttpCode;
use Skyeng\MarketingCmsBundle\Tests\ApiTester;
use Skyeng\MarketingCmsBundle\Tests\DataFixtures\TemplateComponentFixtures;
use Skyeng\MarketingCmsBundle\Tests\DataFixtures\TemplateFixtures;

class SaveTemplateComponentsV1Cest
{
    private const URL = '/api/v1/cms/save-template-components';

    public function _before(ApiTester $I): void
    {
        $I->haveJsonHeaders();
        $I->haveFixtures([TemplateFixtures::class, TemplateComponentFixtures::class]);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->sendPost(self::URL, [
            'templateId' => '03f5f056-8668-4eaa-977d-9e07f05707c0',
            'components' => [
                [
                    'id' => '9bd4772e-0acd-4e4d-8083-1ed3c1c9d4f3',
                    'isPublished' => true,
                    'selector' => 'catalog-test-component',
                    'data' => '{"title":"\u0444\u0430\u044b\u0444\u0430\u044b\u0432","image":"https:\/\/cdn.skyeng.ru\/uploads\/image.png","text":"kalfddaf","certificates":[]}',
                    'order' => 1,
                ],
                [
                    'id' => null,
                    'isPublished' => false,
                    'selector' => 'catalog-test-component-2',
                    'data' => null,
                    'order' => 2,
                ],
            ],
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->sendPost(self::URL, [
            'templateId' => '03f5f056-8668-4eaa-977d-9e07f05707c0',
            'components' => 'text',
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);

        $I->sendPost(self::URL, [
            'templateId' => null,
            'components' => [],
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->sendPost(self::URL, [
            'templateId' => '03f5f056-8668-4eaa-977d-9e07f05707c0',
            'components' => [],
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(
            [
                'message' => 'string',
                'data' => 'array',
            ]
        );
    }
}
