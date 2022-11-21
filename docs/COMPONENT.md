# Компоненты

## Описание

*Компоненты* - это визуальные блоки на странице, растянутые на всю ширину.

Frontend-часть:
- рендерит компоненты.
- предоставляет [редактор](https://gitlab.skyeng.link/skyeng/marketing-cms-bundle/-/blob/master/docs/CMS_EDITOR.md) для CRUD-операций с компонентами.

Бэкенд-часть:
- предоставляет api для сохранения данных компонента.

## Структура компонента

- `id`
- `page` - Id страницы, на которой он отображается
- `field` - Id поля модели, на которой он отображается
- `name` - это имя селектора в ssr приложении, с помощью него ssr понимает как рендерить этот компонент.
- `data` - данные компонента, хранятся в формате json
- `order` - позиция компонента на странице
- `isPublished` - компоненты можно прятать, если по каким-то причинам не хотим их пока отображать

У компонента обязательно должен быть Page или Field. Сами по себе компоненты существовать не могут.

## API

- фронтенд для получения компонентов Page использует `GET /api/v1/cms/get-page` в котором кроме сео данных страницы, передаются и компоненты
- для получения компонентов модели используется ендпоинт: `GET /api/v1/cms/get-models`