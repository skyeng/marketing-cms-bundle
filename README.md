# Marketing CMS Bundle

## Краткое описание

Пакет, предоставляющий возможности CMS (управление контентом сайта). Можно подключать к другим проектам, использующим EasyAdmin.

## Ответственные:

-   Code owner: [Александр Володин](https://skyeng.slack.com/team/U01EW8X27M5)
-   Product Owner: [Дмитрий Бормотов](https://skyeng.slack.com/team/U8V9M21M5)
-   Команда: CISDevelopment

## Slack-каналы

-   Главный канал: [#marketing-cms-dev](https://skyeng.slack.com/archives/C02H1DCDDUY)
-   Канал для MR: [#marketing-code-review](https://skyeng.slack.com/archives/CS3911LA1)

## Ссылка на общую документацию

-   https://confluence.skyeng.tech/x/5bP6Bw

## Технологический стек

-   PHP 8 + Symfony 5.4 + EasyAdmin 4

# Установка

-   Добавляем в composer.json новый репозиторий:

```json
{
    "type": "vcs",
    "url": "git@gitlab.skyeng.link:skyeng/marketing-cms-bundle.git"
}
```

-   Подключаем пакет

    `composer require skyeng/marketing-cms-bundle:^4.0`

-   Добавляем бандл в bundles.php

```php
    Skyeng\MarketingCmsBundle\MarketingCmsBundle::class => ['all' => true]
```

-   Прописываем роуты для API

```yaml
# config/routes/marketing_cms.yaml

marketing_cms:
    resource: "@MarketingCmsBundle/Resources/config/routes.yaml"
```

-   При наличии NelmioApiDocBundle копируем в его конфиг definitions из `vendor/skyeng/marketing-cms-bundle/src/Resources/config/packages/nelmio_api_doc.yaml`

-   Генерим миграции для сущностей CMS

    `bin/console make:migration`

    Правильность сгенерированных миграций можно проверить по маппигам: src/Infrastructure/Doctrine/Entity/mapping/

-   Добавляем пункты меню в DashboardController EasyAdmin-а

```php
    use Skyeng\MarketingCmsBundle\Domain\Entity\File;
    use Skyeng\MarketingCmsBundle\Domain\Entity\Redirect;
    use Skyeng\MarketingCmsBundle\Domain\Entity\Template;

    class DashboardController extends AbstractDashboardController
    {
        public function configureMenuItems(): iterable
        {
            ...
            yield MenuItem::section('CMS');
            yield MenuItem::linkToCrud('Файлы', 'fas fa-folder-open', File::class);
            yield MenuItem::linkToCrud('Редиректы', 'fas fa-folder-open', Redirect::class);
            yield MenuItem::linkToCrud('Шаблоны компонентов', 'fas fa-folder-open', Template::class);
            ...
        }
    }
```

### Настройка Медиа библиотеки

-   Определить переменную окружения UPLOADS_BASE_URL, она будет использована как uri_prefix для медиа файлов

-   По-умолчанию в маппинге vich_uploader для медиа файлов указан upload_destination: 'upload.nfs', его можно переопределить:

```yaml
# config/packages/vich_uploader.yaml

vich_uploader:
    mappings:
        cms_media_files:
            upload_destination: "uploads.nfs" # Указать свой
```

-   Также можно переопределить конфиг vich_uploader для prod окружения:

```yaml
# config/packages/prod/vich_uploader.yaml

vich_uploader:
    mappings:
        cms_media_files:
            upload_destination: "uploads.s3" # Указать свой
```

-   Добавляем пункты меню в DashboardController easyadmin-а

```php
    use Skyeng\MarketingCmsBundle\Domain\Entity\MediaCatalog;
    use Skyeng\MarketingCmsBundle\Domain\Entity\MediaFile;

    class DashboardController extends AbstractDashboardController
    {
        public function configureMenuItems(): iterable
        {
            ...
            yield MenuItem::section('CMS Media');
            yield MenuItem::linkToCrud('Файлы', 'fas fa-folder-open', MediaFile::class);
            yield MenuItem::linkToCrud('Каталоги', 'fas fa-folder-open', MediaCatalog::class);
            ...
        }
    }
```

-   Для управления требованиями к загружаемым файлам настраиваем валидацию. Скопируйте конфиг [validation.yaml](https://gitlab.skyeng.link/skyeng/marketing-cms-bundle/-/blob/master/src/Resources/config/default/validator/validation.yaml) в `config/validator/validation.yaml`

-   Вы можете определить свой набор типов файлов и их максимальный объем. На максимальный объем файла также влияют настройки php и nginx

# Другие вопросы

#### [1. Компоненты](https://gitlab.skyeng.link/skyeng/marketing-cms-bundle/-/blob/master/docs/COMPONENT.md)

#### [2. Готовые Компоненты](https://gitlab.skyeng.link/skyeng/marketing-cms-bundle/-/blob/master/docs/TEMPLATE_COMPONENT.md)

#### [3. Модели](https://gitlab.skyeng.link/skyeng/marketing-cms-bundle/-/blob/master/docs/MODEL.md)

#### [4. CMS Editor](https://gitlab.skyeng.link/skyeng/marketing-cms-bundle/-/blob/master/docs/CMS_EDITOR.md)
