<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Api\V1\Cms;

use Codeception\Exception\ModuleException;
use Codeception\Util\HttpCode;
use Skyeng\MarketingCmsBundle\Tests\ApiTester;
use Skyeng\MarketingCmsBundle\Tests\DataFixtures\MediaCatalogFixtures;
use Skyeng\MarketingCmsBundle\Tests\DataFixtures\MediaFileFixtures;

class RemoveMediaFilesV1Cest
{
    private const URL = '/api/v1/cms/remove-media-files';

    public function _before(ApiTester $I): void
    {
        $I->haveFixtures([
            MediaCatalogFixtures::class,
            MediaFileFixtures::class,
        ]);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->sendPost(self::URL, [
            'ids' => [
                '64513a50-d815-4e6a-81b3-39e0076646dd',
                '58246d21-5b2d-4d3b-9448-64191db943d2',
            ],
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->sendPost(self::URL, ['unexpected_param' => 'test']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);

        $I->sendPost(self::URL, [
            'ids' => [
                '8263ad56-4129-4266-847d-bf9c947d5540', // несуществующий id медиа файла
            ],
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->sendPost(self::URL, [
            'ids' => [
                '64513a50-d815-4e6a-81b3-39e0076646dd',
                '58246d21-5b2d-4d3b-9448-64191db943d2',
            ],
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
