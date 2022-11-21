<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Api\V1\Cms;

use Codeception\Exception\ModuleException;
use Codeception\Util\HttpCode;
use Skyeng\MarketingCmsBundle\Tests\ApiTester;
use Skyeng\MarketingCmsBundle\Tests\DataFixtures\FileFixtures;
use Skyeng\MarketingCmsBundle\Tests\DataFixtures\RedirectFixtures;

class GetFileV1Cest
{
    private const URL = '/api/v1/cms/get-file';

    public function _before(ApiTester $I): void
    {
        $I->haveFixtures([FileFixtures::class, RedirectFixtures::class]);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->sendGET(self::URL, ['uri' => '/test.txt']);
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
        $I->sendGET(self::URL, ['uri' => '/test.json']);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(
            [
                'structure' => [
                    'int' => 'integer',
                    'string' => 'string',
                ],
            ]
        );
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturnRedirectIfExists(ApiTester $I): void
    {
        $I->stopFollowingRedirects();
        $I->sendGET(self::URL, ['uri' => '/test-file-redirect.json']);
        $I->seeResponseCodeIs(301);
        $I->seeHttpHeader('Location', 'https://skyeng.ru');
    }
}
