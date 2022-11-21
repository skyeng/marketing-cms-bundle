<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Functional\HeadlessCms;

use Codeception\Util\HttpCode;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\EventSubscriber\ModelWrapperAliasCreator;
use Skyeng\MarketingCmsBundle\Tests\DataFixtures\HeadlessCms\Crud\FieldFixtures;
use Skyeng\MarketingCmsBundle\Tests\FunctionalTester;
use Skyeng\MarketingCmsBundle\Utils\SymfonyApplication\Controller\DashboardController;

class AdminCrudCest
{
    private function getAdminUrlGenerator(FunctionalTester $I): AdminUrlGenerator
    {
        $I->getService(ModelWrapperAliasCreator::class);

        return $I->getService(AdminUrlGenerator::class)->setDashboard(DashboardController::class);
    }

    public function indexPage(FunctionalTester $I): void
    {
        $I->haveFixtures([FieldFixtures::class]);
        $url = $this->getAdminUrlGenerator($I)
            ->setController('article.CRUD')
            ->generateUrl();

        $I->amOnPage($url);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->see('article_title1');
        $I->dontSee('webinar_title1');

        $url = $this->getAdminUrlGenerator($I)
            ->setController('webinar.CRUD')
            ->generateUrl();

        $I->amOnPage($url);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->see('webinar_title1');
        $I->dontSee('article_title1');
    }

    public function newPage(FunctionalTester $I): void
    {
        $url = $this->getAdminUrlGenerator($I)
            ->setController('article.CRUD')
            ->setAction('new')
            ->generateUrl();

        $I->amOnPage($url);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function createModel(FunctionalTester $I): void
    {
        $url = $this->getAdminUrlGenerator($I)
            ->setController('article.CRUD')
            ->setAction('new')
            ->generateUrl();

        $I->amOnPage($url);
        $I->see('Create');

        $I->submitForm(
            '#new-Model-form',
            [
                'ea' => ['newForm' => ['btn' => 'saveAndReturn']],
                'Model' => [
                    'Text--title' => 'new_title',
                    'Text--url' => 'new_url',
                    'Text--description' => 'new_description',
                    'Textarea--content' => 'new_content',
                    'Boolean--published' => '1',
                    'DateTime--published_at' => '2021-10-23T23:44',
                ],
            ]
        );

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->see('Edit');
        $I->see('new_title');
    }

    public function editModel(FunctionalTester $I): void
    {
        $I->haveFixtures([FieldFixtures::class]);
        $url = $this->getAdminUrlGenerator($I)
            ->setController('article.CRUD')
            ->setAction('edit')
            ->setEntityId('e9569f15-c58d-490d-823f-068d032ab67c')
            ->generateUrl();

        $I->amOnPage($url);
        $I->submitForm(
            '#edit-Model-form',
            [
                'ea' => ['newForm' => ['btn' => 'saveAndReturn']],
                'Model' => [
                    'Text--title' => 'edited_title',
                    'Text--url' => 'edited_url',
                    'Text--description' => 'edited_description',
                    'Textarea--content' => 'edited_content',
                    'Boolean--published' => '1',
                    'DateTime--published_at' => '2021-10-23T23:44',
                ],
            ]
        );

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->see('Edit');
        $I->see('edited_title');
    }
}
