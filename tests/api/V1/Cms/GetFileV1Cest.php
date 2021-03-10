<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Api\V1\Cms;

use Skyeng\MarketingCmsBundle\Tests\ApiTester;
use Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\DataFixtures\FileFixtures;
use Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\DataFixtures\RedirectFixtures;
use Codeception\Util\HttpCode;

class GetFileV1Cest
{
    /**
     * @var string
     */
    private $url = '/api/v1/cms/get-file';

    public function _before(ApiTester $I)
    {
        $I->haveFixtures([FileFixtures::class, RedirectFixtures::class]);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->sendGET($this->url, ['uri' => '/test.txt']);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->sendGET($this->url, ['unexpected_param' => 'test']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->sendGET($this->url, ['uri' => '/test.json']);
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
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturnRedirectIfExists(ApiTester $I): void
    {
        $I->stopFollowingRedirects();
        $I->sendGET($this->url, ['uri' => '/test-file-redirect.json']);
        $I->seeResponseCodeIs(301);
        $I->seeHttpHeader('Location', 'https://skyeng.ru');
    }
}
