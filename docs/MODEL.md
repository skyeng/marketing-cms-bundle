# Model

[1. Описание](#описание)

[2. Создание модели](#создание_модели)

[2.1. Описание структуры модели](#описание_структуры_модели)

[2.2. Отображаем CRUD в админ-панели](#отображаем_crud_в_админ_панели)

[3. Конфигурация полей](#конфигурация_полей)

[3.1. Список доступных настроек](#список_доступных_настроек)

[3.2. Пример настроек](#пример_настроек)

[3.3. Получение данных конфигурации полей](#получение_данных_конфигурации_полей)

[3.4. Список типов полей](#список_типов_полей)

[3.5. Хуки полей](#хуки_полей)

[3.5.1. Список существующих хуков из бандла](#cписок_существующих_хуков_из_бандла)

[3.5.2. Устройство работы хуков](#устройство_работы_хуков)

[3.5.3. Как создавать свои хуки](#как_создавать_свои_хуки)

[3.6. Валидация уникальности данных в полях](#валидация_уникальности_данных_в_полях)

[4. Получение данных](#получение_данных)

[5. Хранение данных](#хранение_данных)

## 1.Описание <a name="описание"></a>

Модели были разработаны на смену обычных страниц (Page), когда возник запрос на создание структур данных с разными наборами полей.
Обычные страницы имею определенный список полей, который невозможно изменить, а в большинстве проектов требуется разная структура страниц.

## 2. Создание модели <a name="создание_модели"></a>

### 2.2. Описание структуры модели <a name="описание_структуры_модели"></a>

```yaml
# config/packages/marketing_cms.yaml

marketing_cms:
    models:
        article:
            name: article             # Алиас модели
            label: Статья             # Название, используется в админ-панели
            cloneable: true           # Можно клонировать
            fields:                   # Список полей

                - name: title         # Алиас поля
                  type: Text          # Тип поля
                  label: Название     # Название поля русском
                  localized: true     # Значение зависит от разных локалей

                - name: url
                  type: Text
                  label: Ссылка
```

Пример конфига можно найти [тут](https://gitlab.skyeng.link/skyeng/marketing-cms-bundle/-/blob/master/src/Resources/config/default/marketing_cms.yaml).

### 2.2. Отображаем CRUD в админ-панели <a name="отображаем_crud_в_админ_панели"></a>

Для отображения ссылки на модель добавьте следующее в DashboardController EasyAdmin-а

```php
    use Skyeng\MarketingCmsBundle\Domain\Entity\Model;

    class DashboardController extends AbstractDashboardController
    {
        public function configureMenuItems(): iterable
        {
            $modelTitle = 'Статьи'; // Название пункта меню
            $modelName = 'article'; // Алиас модели
            ...
            yield MenuItem::linkToCrud($modelTitle, 'fas fa-folder-open', Model::class)
                ->setController("{$modelName}.CRUD");
            ...
        }
    }
```

## 3. Конфигурация полей <a name="конфигурация_полей"></a>

### 3.1. Список доступных настроек <a name="список_доступных_настроек"></a>

| Название      | Обязательное | Тип    | По-умолчанию | Описание                                                                                                                                         |
|---------------|--------------|--------|--------------|--------------------------------------------------------------------------------------------------------------------------------------------------|
| name          | да           | string | -            | Алиас, нужно писать на английском.                                                                                                               |
| type          | да           | string | -            | Тип, список доступных типов [тут](#список_типов_полей).                                                                                          |
| label         | да           | string | -            | Название поля на русском.                                                                                                                        |
| localized     | нет          | bool   | false        | Локализация. Если true, то можно будет создавать разные данные для разных локалей.                                                               |
| required      | нет          | bool   | true         | Обязательность. Если true, то в форме редактирования нужно будет обязательно заполнять эти поля.                                                 |
| cloneable     | нет          | bool   | true         | Клонирование. Если true, то данные из клонируемой модели будут в новой модели. Например, отключать полезно для уникальных или технических полей. |
| hide_on_index | нет          | bool   | false        | Если true, то поле скрыто в списке моделей. Например, полезно вешать `true` на все поля, кроме `title`, `createdAt`.                             |
| hide_on_form  | нет          | bool   | false        | Если true, то поле скрыто в форме моделей. Например, полезно вешать на поля, которые мы не хотим редактировать вручную `createdAt`, `updatedAt`. |
| group         | нет          | string | -            | Группировка полей в форме редактирования. Значение нужно задавать на русском, это не алиас.                                                      |
| hooks         | нет          | array  | -            | Скрипты, которые выполняют определенные действия с данными этого поля при определенных событиях. Список хуков ниже.                              |
| options       | нет          | array  | -            | Вспомогательные данные.                                                                                                                          |

### 3.2. Пример настроек <a name="пример_настроек"></a>

```yaml
    ...
    fields:                       # Список полей

        - name: title             # Алиас поля
          type: Text              # Тип поля
          label: Название         # Название поля русском
          localized: true         # Значение зависит от разных локалей
          group: Основные данные  # Группировка поля в форме EasyAdmin
          required: true          # Обязательность заполнения поля при редактировании
          cloneable: false        # Не клонировать данные
          hide_on_form: true      # Не показывать в форме редактирования
          hide_on_index: true     # Не показывать в списке
          hooks:                  # Список Хуков
            - setPrefixOnClone    # Добавляет префикс при клонировании модели
          
```

### 3.3. Получение данных конфигурации полей <a name="полуение_данных_конфигурации_полей"></a>

Для получения данных о том как настроены поля в той или иной модели, можно использовать сервис `ModelsConfiguration`.
Он хранит информацию в объектно-ориентированном виде.

### 3.4. Список типов полей <a name="список_типов_полей"></a>

- `Text`
- `Textarea`
- `Integer`
- `Boolean`
- `DateTime`
- `Choice` - Выпадающий список. Сам список можно задать в настройке `options` -> `choices`
- `Components` - Такой тип позволяет добавлять компоненты у моделей. Поле с таким типом должно быть только одно у модели.

### 3.5. Хуки полей <a name="хуки_полей"></a>

Выполняют определенные действия с данными этого поля при определенных событиях. Можно использовать уже существующие хуки, а также создавать свои.

#### 3.5.1. Список существующих хуков из бандла: <a name="cписок_существующих_хуков_из_бандла"></a>

- `setCurrentDateTimeOnCreate` - Устанавливает в поле текущую дату при создании модели. Полезно для `createdAt` и `updatedAt`.
- `setCurrentDateTimeOnUpdate` - Устанавливает в поле текущую дату при редактировании модели. Полезно для `updatedAt`.
- `setPrefixOnClone` - Добавляет к значению поля префикс при клонировании. Полезно для уникальных полей, например `title` и `slug`.

#### 3.5.2. Устройство работы хуков: <a name="устройство_работы_хуков"></a>

- все хуки реализуют `HookInterface`, у которого есть два метода: 

 `HookInterface::supports(HookEventInterface): bool`, нужен для проверки, поддерживает хук обработку этих событий или нет.
 
 `HookInterface::handle(HookEventInterface $hookEvent): void` - нужен для обработки событий.

- `HookManager` - это центральный сервис, который принимает события и отправляет их на обработку в нужные хуки. Для этого у него есть метод `handle`.
- Наподобие доктриновских эвентов (Doctrine Event), у хуков есть свои Event'ы (`PreCreateHookEvent` и `PreUpdateHookEvent` с общим интерфейсом `HookEventInterface`) - это события, на которые подписаны хуки. Евенты обязательно должны содержать Модели.

#### 3.5.3. Как создавать свои хуки: <a name="как_создавать_свои_хуки"></a>

*Кейс 1*: если мне нужно, чтобы при создании у текстового поля генерировался уникальный контент: 
- в своём проекте создаем свой хук реализуя `HookInterface` и в методе `supports` проверяем объект на поддержку события `PreCreateHookEvent`.
- в методе `handle`, у нашего хука, вытаскиваем модель из события, находим в ней нужное поле и задаем ему своё значение. Сохранять через `em` не надо. Пример можно найти в `SetCurrentDateTimeOnCreateHook`.

*Кейс 2*: я хочу чтобы при клонировании добавлялась приписка у текстового поля:
- Добавляем новое событие `PreCloneHookEvent` реализующий `HookEventInterface`.
- Создаем свой хук на основе `HookInterface` и в методе `supports` проверяем на поддержку `PreCloneHookEvent`. В методе `handle` реализуем нашу логику, аналогично кейсу 1.
- В сервисе клонирования вызываем сервис `HookManager -> handle` и передаем туда `new PreCloneHookEvent($model)`. Пример можно найти в сервисе `CloneModelService`.

### 3.6. Валидация уникальности данных в полях <a name="валидация_уникальности_данных_в_полях"></a>

В файл `config/validator/validation.yaml` добавляем правила валидации для модели.

Для валидации уникальности полей был создан Constraint: `Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Validator\Constraints\UniqueField`.

`fieldName` - Валидируемое поле, значение которого должно быть уникальным.

`modelName` - Валидируемая модель. Если не указать, то будет проверяться на уникальность глобально по всем моделям.

`message` - Текст ошибки валидации.

Пример:

```yaml
Skyeng\MarketingCmsBundle\Domain\Entity\Model:
  constraints:
    - Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Validator\Constraints\UniqueField:
        fieldName: url
        message: 'Это значение ссылки уже используется в какой-то из моделей'
    - Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Validator\Constraints\UniqueField:
        fieldName: title
        modelName: webinar
        message: 'Это название страницы уже используется у вебинаров'
```

## 4. Получение данных <a name="получение_данных"></a>

- `GET /api/v1/cms/get-models` - получение реализаций конкретной модели, с возможностью фильтрации, сортировки и пагинации.
- `GET /api/v1/cms/get-model` - получение одной реализации модели по id.

В обоих ендпоинтах можно передавать данные о локали `locale`, чтобы подтягивать данные только для нужной локали.

## 5. Хранение данных <a name="хранение_данных"></a>

Данные по моделям хранятся в двух таблицах:

- `cms_model` - хранит id реализаций моделей

| столбец    | id            | name                              |
|------------|---------------|-----------------------------------|
| тип данных | uuid          | string                            |
| назначение | id реализации | алиас модели, которую реализовали |

- `cms_field` - хранит данные полей модели

| столбец    | id                 | model_id             | name       | value       | type                | locale       |
|------------|--------------------|----------------------|------------|-------------|---------------------|--------------|
| тип данных | uuid               | uuid                 | string     | string/null | string              | string/null  |
| назначение | id реализации поля | id реализации модели | алиас поля | данные      | тип хранимых данных | алиас локали |