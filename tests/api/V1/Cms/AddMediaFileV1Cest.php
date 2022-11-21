<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Api\V1\Cms;

use Codeception\Exception\ModuleException;
use Codeception\Util\HttpCode;
use Skyeng\MarketingCmsBundle\Tests\ApiTester;

class AddMediaFileV1Cest
{
    private const URL = '/api/v1/cms/add-media-file';

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        copy(__DIR__.'/AddMediaFileV1Cest_image.png', __DIR__.'/../../../_data/AddMediaFileV1Cest_image.png');

        $I->haveHttpHeader('Content-Type', 'multipart/form-data');
        $I->sendPost(self::URL, [], [
            'file' => __DIR__.'/../../../_data/AddMediaFileV1Cest_image.png',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'multipart/form-data');
        $I->sendPost(self::URL, [], []);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }
}
