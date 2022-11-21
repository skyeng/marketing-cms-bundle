# CMS Editor

## Описание

CMS Editor - это редактор для работы с компонентами страницы (в будущем ещё с компонентами модели).
Он позволяет менять состояние компонентов и тут же видеть результат изменений. 
Решение сделано по образу и подобию как у Tilda и это намного удобнее чем работать с формами в EasyAdmin.

CMS Editor написан на ангуляре, работает соответственно в ssr-приложении.
Подробнее можно почитать в [Confluence: CMS - фронтовая часть](https://confluence.skyeng.tech/pages/viewpage.action?pageId=133870565#id-ДокументацияпоCMS-Фронтоваячасть).

Взаимодействует CMS Editor с backend'ом через специальные ендпоинты

### Ендпоинты для работы с Model
- Получение списка компонентов модели: `POST /api/v1/cms/get-model-components`
- Сохранение компонентов модели: `POST /api/v1/cms/save-model-components`

### Ендпоинты для работы с Template
- Получение списка готовых компонентов: `GET /api/v1/cms/get-templates`
- Получение компонентов готового компонента: `GET /api/v1/cms/get-template-components`
- Сохранение компонентов готового компонента: `POST /api/v1/cms/save-template-components`

### Ендпоинты для работы с Media File
- Добавление нового медиа-файла: `POST /api/v1/cms/add-media-file`
- Удаление медиа-файлов: `POST /api/v1/cms/remove-media-files`

## Настройка

Для отображения ссылки на CMS Editor и задания роли, необходимо внести правки в marketing_cms.yaml
```yaml
marketing_cms:
    editor:
        show_editor_link: true
        security:
            enabled: true
            roles:
                - YOUR_ADMIN_ROLE_IN_EASY_ADMIN
```

После этого можно пользоваться ендпоинтами редактора.

Для того чтобы создавалась иллюзия работы CMS Editor в админке, можно настроить nginx в бэкенд приложении следующим образом:
```text
    location /admin/cms-editor {
        proxy_pass https://<%= ENV.fetch('BASE_HOST') %>;
    }
```
`BASE_HOST` - это хост фронтового приложения, где находится сам CMS Editor.

Для того чтобы статичные файлы (такие, как js, css) нормально отображались, нужно также добавить такое правило:
```text
    location / {
        try_files $uri https://<%= ENV.fetch('BASE_HOST') %>$uri /index.php$is_args$args;
    }
```
Из этого правила следует, что сначала nginx попытается найти ресурс на стороне бэкенда `try_files $uri`.
В случае неудачи будет искать ресурс на стороне фронтового приложения `try_files https://<%= ENV.fetch('BASE_HOST') %>$uri`
Если и там его нет, то будет обрабатываться на бэкенде через `try_files /index.php$is_args$args`
