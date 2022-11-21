<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Api\V1\Cms;

use Codeception\Exception\ModuleException;
use Codeception\Util\HttpCode;
use Skyeng\MarketingCmsBundle\Tests\ApiTester;
use Skyeng\MarketingCmsBundle\Tests\DataFixtures\TemplateComponentFixtures;
use Skyeng\MarketingCmsBundle\Tests\DataFixtures\TemplateFixtures;

class GetTemplateComponentsV1Cest
{
    private const URL = '/api/v1/cms/get-template-components';

    public function _before(ApiTester $I): void
    {
        $I->haveJsonHeaders();
        $I->haveFixtures([
            TemplateFixtures::class,
            TemplateComponentFixtures::class,
        ]);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->sendGET(self::URL, ['templateId' => '03f5f056-8668-4eaa-977d-9e07f05707c0']);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->sendGET(self::URL, ['unexpected_param' => 'test']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->sendGET(self::URL, ['templateId' => '03f5f056-8668-4eaa-977d-9e07f05707c0']);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'result' => [
                    'title' => 'string',
                    'components' => 'array',
                ],
            ],
            'message' => 'string',
        ]);

        $I->seeResponseMatchesJsonType([
            'id' => 'string',
            'selector' => 'string',
            'data' => 'array',
            'order' => 'integer',
        ], 'data.result.components.*');
    }
}
