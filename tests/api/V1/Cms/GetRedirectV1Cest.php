<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Api\V1\Cms;

use Skyeng\MarketingCmsBundle\Tests\ApiTester;
use Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\DataFixtures\RedirectFixtures;
use Codeception\Util\HttpCode;

class GetRedirectV1Cest
{
    /**
     * @var string
     */
    private $url = '/api/v1/cms/get-redirects';

    public function _before(ApiTester $I)
    {
        $I->haveFixtures([RedirectFixtures::class]);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->sendGET($this->url);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->sendGET($this->url);
        $I->seeResponseMatchesJsonType(
            [
                'data' => [
                    'result' => [
                        [
                            'from' => 'string',
                            'to' => 'string',
                            'httpCode' => 'integer',
                        ]
                    ],
                ],
            ]
        );
    }
}
