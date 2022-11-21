<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Api\V1\Cms;

use Codeception\Exception\ModuleException;
use Codeception\Util\HttpCode;
use Skyeng\MarketingCmsBundle\Tests\ApiTester;
use Skyeng\MarketingCmsBundle\Tests\DataFixtures\TemplateFixtures;

class GetTemplatesV1Cest
{
    private const URL = '/api/v1/cms/get-templates';

    public function _before(ApiTester $I): void
    {
        $I->haveJsonHeaders();
        $I->haveFixtures([
            TemplateFixtures::class,
        ]);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->sendGET(self::URL);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->sendGET(self::URL);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'result' => 'array',
            ],
            'message' => 'string',
        ]);

        $I->seeResponseMatchesJsonType([
            'id' => 'string',
            'title' => 'string',
            'isTemplate' => 'boolean',
        ], 'data.result.*');
    }
}
