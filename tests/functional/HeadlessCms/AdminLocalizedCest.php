<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Functional\HeadlessCms;

use Codeception\Util\HttpCode;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Skyeng\MarketingCmsBundle\Domain\Entity\Field;
use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\EventSubscriber\ModelWrapperAliasCreator;
use Skyeng\MarketingCmsBundle\Tests\DataFixtures\HeadlessCms\Localization\ComponentFixtures;
use Skyeng\MarketingCmsBundle\Tests\DataFixtures\HeadlessCms\Localization\FieldFixtures;
use Skyeng\MarketingCmsBundle\Tests\FunctionalTester;
use Skyeng\MarketingCmsBundle\Utils\SymfonyApplication\Controller\DashboardController;

class AdminLocalizedCest
{
    private function getAdminUrlGenerator(FunctionalTester $I): AdminUrlGenerator
    {
        $I->getService(ModelWrapperAliasCreator::class);

        return $I->getService(AdminUrlGenerator::class)->setDashboard(DashboardController::class);
    }

    public function createModel(FunctionalTester $I): void
    {
        $url = $this->getAdminUrlGenerator($I)
            ->setController('tag.CRUD')
            ->setAction('new')
            ->generateUrl();

        $I->amOnPage($url);
        $I->see('Create');

        $I->submitForm(
            '#new-Model-form',
            [
                'ea' => ['newForm' => ['btn' => 'saveAndReturn']],
                'Model' => [
                    'Text--slug' => 'AUTO',
                    'Text-en-title' => 'car',
                ],
            ]
        );

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->assertCount(1, $I->grabEntitiesFromRepository(Model::class));
        $I->assertCount(3, $I->grabEntitiesFromRepository(Field::class));

        $I->see('Edit');
        $I->see('car');
    }

    public function editModel(FunctionalTester $I): void
    {
        $I->haveFixtures([FieldFixtures::class, ComponentFixtures::class]);
        $url = $this->getAdminUrlGenerator($I)
            ->setController('tag.CRUD')
            ->setAction('edit')
            ->setEntityId('fc22f509-61ea-4b8d-8235-20dd667cd438')
            ->generateUrl();

        $I->amOnPage($url);
        $I->submitForm(
            '#edit-Model-form',
            [
                'ea' => ['newForm' => ['btn' => 'saveAndReturn']],
                'Model' => [
                    'Text--slug' => 'AUTO',
                    'Text-en-title' => 'edited_car',
                ],
            ]
        );

        $I->seeResponseCodeIs(HttpCode::OK);

        /** @var Model $model */
        $model = $I->grabEntityFromRepository(Model::class, ['id' => 'fc22f509-61ea-4b8d-8235-20dd667cd438']);
        $I->assertEquals('edited_car', $model->{'Text-en-title'});

        $I->see('Edit');
        $I->see('edited_car');
    }
}
