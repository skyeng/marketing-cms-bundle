<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Utils\SymfonyApplication\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Menu\CrudMenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Menu\SectionMenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Iterator;
use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Domain\Entity\Template;

class DashboardController extends AbstractDashboardController
{
    /**
     * @return Iterator<CrudMenuItem|SectionMenuItem>
     */
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Готовые компоненты страниц', 'fas fa-folder-open', Template::class);

        yield MenuItem::section('ЦМС на конфигах');

        yield MenuItem::linkToCrud('Статьи', 'fas fa-folder-open', Model::class)->setController('article.CRUD');

        yield MenuItem::linkToCrud('Вебинары', 'fas fa-folder-open', Model::class)->setController('webinar.CRUD');

        yield MenuItem::linkToCrud('Теги', 'fas fa-folder-open', Model::class)->setController('tag.CRUD');
    }
}
