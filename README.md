# MarketingCmsBundle

## Краткое описание
Добавляет в проект с easyadmin функционал CMS (статические файлы, редиректы и конструктор страниц).

## Ответственные:
* Code owner: Сергей Романов
* Product Owner: Иван Бодяк
* Команда: Marketing Core (C0)

## Slack-каналы 
* [#marketing-core](https://skyeng.slack.com/archives/CNJ4ZEQ1Z)
* [#marketing-code-review](https://skyeng.slack.com/archives/CS3911LA1)

## Ссылка на общую документацию
https://confluence.skyeng.tech/x/SLMhBQ

## Технологический стек
* PHP 7.3 + Symfony 4.4

# Установка

* Добавляем в composer.json новый репозиторий:
    ```json
    {
        "type": "vcs",
        "url": "git@github.com:skyeng/marketing-cms-bundle.git"
    }
    ```
* Подключаем пакет
  
    `composer require skyeng/marketing-cms-bundle:^1.0`  
  
* Добавляем бандл в bundles.php
    ```php
    Skyeng\MarketingCmsBundle\MarketingCmsBundle::class => ['all' => true]
    ```

* Прописываем роуты для API
    ```yaml
    # config/routes/marketing_cms.yaml

    marketing_cms:
      resource: '@MarketingCmsBundle/Resources/config/routes.yaml'
    ```
* При наличии NelmioApiDocBundle копируем в его конфиг definitions из `vendor/skyeng/marketing-cms-bundle/src/Resources/config/packages/nelmio_api_doc.yaml`

* Генерим миграции для сущностей CMS

  `bin/console make:migration`