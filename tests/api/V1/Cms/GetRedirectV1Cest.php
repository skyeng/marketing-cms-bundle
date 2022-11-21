<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Api\V1\Cms;

use Codeception\Exception\ModuleException;
use Codeception\Util\HttpCode;
use Skyeng\MarketingCmsBundle\Tests\ApiTester;
use Skyeng\MarketingCmsBundle\Tests\DataFixtures\RedirectFixtures;

class GetRedirectV1Cest
{
    private const URL = '/api/v1/cms/get-redirects';

    public function _before(ApiTester $I): void
    {
        $I->haveFixtures([RedirectFixtures::class]);
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
        $I->seeResponseMatchesJsonType(
            [
                'data' => [
                    'result' => [
                        [
                            'from' => 'string',
                            'to' => 'string',
                            'httpCode' => 'integer',
                        ],
                    ],
                ],
            ]
        );
    }
}
