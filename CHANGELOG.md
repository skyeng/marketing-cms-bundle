# 4.0.2
Александр Володин
* Привел в порядок названия констант Rector'а

# 4.0.1
Александр Володин
* Теперь вызываются хуки обновлений при сохранении компонентов модели и готовых компонентов
* Поправил правила rector, улучшил типизацию

# 4.0.0
Александр Володин

* Трансформировал код на `PHP 8` с помощью `Rector` 
* Добавил поддержку `easy-admin` ^4.0.
* Поправил easy-admin контроллеры в UI/Controller/Admin/*.
* Добавил `ModelFactory` и вызов `ModelFactory::create` вместо `new Model`.
* Добавил `TemplateFactory` и вызов `TemplateFactory::create` вместо `new Template`.
* Удалил сервис клонирования моделей `CloneModelService`, а логику перенес в `ModelFactory::clone`. Также при клонировании больше не осуществляется валидация и сохранение. Для валидации создан сервис `ModelValidator`.
* Удалил сервис клонирования готовых компонентов `CloneTemplateService`, а логику перенес в `TemplateFactory::clone`. Сохранение клона теперь осуществляется вне метода клон.
* Перенес `..\Domain\Factory\ComponentFactory.php` в `..\Domain\Factory\Component\ComponentFactory.php`, а также его интерфейс `ComponentFactoryInterface.php`.
* Перенес `..\Infrastructure\DependencyInjection` в `..\Infrastructure\Symfony\DependencyInjection`.
* Перенес `..\Infrastructure\Symfony\Locale` в `..\Infrastructure\Symfony\RequestStack\Locale`.
* Внес изменения в тесты.
* Перенес `DataFixtures` из `src/Infrastructure/Doctrine` в `tests/_support`.
* Установил `deptrac` для анализа структуры. Пофиксил ошибки и добавил его запуск в CI.
* Установил `psalm` как доп. стат анализ, задал уровень 6, пофиксил найденные ошибки и добавил его проверку в CI.

Для миграции используйте: Skyeng\MarketingCmsBundle\Utils\Rector\Set\MarketingCmsSetList::UP_TO_40

# 3.4.0
Александр Володин

Архитектурные правки:
* Перенес PaginatorInterface из корня `Domain` в `Service/Paginator`.
* Удалил `Componentable` интерфейс.

Правки рефакторинга:
* Увеличил уровень анализа кода `Phpstan` с 3 до 8. После пофиксил все 70 ошибок.
* Добавил валидацию yaml файлов в gitlab ci. После пофиксил все требования по yaml файлам от линтера.

Для миграции используйте: Skyeng\MarketingCmsBundle\Utils\Rector\Set\MarketingCmsSetList::UP_TO_34

# 3.3.0
Александр Володин
Архитектурные правки:
* Перенес `ValidatorInterface` в слой Application слой.
* Перенес сервисы `Validator` и `UniqueField` и прочие сервисы связанные с валидацией в инфраструктурный слой в раздел `Symfony`.
* Перенес `ResponseFactory` в инфраструктурный слой в раздел `Symfony`.
* Перенес `AuthorizationService` и `CurrentUrlProviderService` из `Infrastructure\Service` в `Infrastructure\Symfony`.
* Перенес миграции из инфраструктурного слоя в отдельную директорию `migrations`.

Правки рефакторинга:
* Обновил поддержку symfony до 5.4, пофиксил все deprecated и поправил запуск `Kernel` в тестовой среде.
* Обновил `nelmio/api-doc-bundle` до 4.9 и поменял аннотации с `@SWG` на `@OA` и в целом пофиксил параметры.
* Перенес всё что связано с symfony приложением (которое используется для теста и локальной проверки) убрал в utils/symfony-application
* Перенес конфиги phpstan, php-cs-fixer, rector в utils
* Почистил директорию src и tests, теперь там ничего лишнего, только исходный код бандла и codeception соответственно. Это разгрузило директории на 19 файлов.
* Добавил .gitattributes, чтобы исключить подтягивание в проект ненужных файлов

Для миграции используйте: Skyeng\MarketingCmsBundle\Utils\Rector\Set\MarketingCmsSetList::UP_TO_33

# 3.2.0
Александр Володин

Архитектурные правки:
* Добавил константы `FieldType` для `Field`ов.
* Изменил интерфейс `FieldRepositoryInterface::findByValue` на `FieldRepositoryInterface::findByName`.
* Переделал генератор респонсов `ResponseFactory`.
* Удалил мертвые сервисы `ComponentFieldOptionsResolver`, `ComponentFieldTypeResolver`, `FormFieldTypeHelper` и dto `ComponentFormFieldDto`, которые использовались для генерации форм компонентов в EasyAdmin.
* Удалил ненужный интерфейс: `ResponseTransformerServiceInterface`.
* Переделал валидацию запросов save-template-components и save-model-components: сделал нативную дисереализацию и валидацию через симфони формы.

Правки рефакторинга:
* Сделал минимальную версию php 7.4.
* Установил и настроил php-cs-fixer и rector.
* Отрефакторил rector'ом качество кода, типизацию, удалил мертвый код, перевел на использование возможностей php 7.4.
* Отрефакторил php-cs-fixer'ом код-стайл.
* Настроил раздел utils/rector для создания и тестирования кастомных правил, а также создания сетов для апгрейда на новые версии.
* Добавил набор правил rector'а для быстрой миграции на новую версию `MarketingCmsSetList::UP_TO_32`.

Для миграции используйте: Skyeng\MarketingCmsBundle\Utils\Rector\Set\MarketingCmsSetList::UP_TO_32

# 3.1.3
Александр Володин
* Добавил передачу текущей локали для CMS Editor

# 3.1.2
Александр Володин
* Модели теперь можно фильтровать филдам с NULL значениям
* UniqueValidator не ругается на значения из других локалей

# 3.1.1
Александр Володин
* пофиксил api и интеграционные тесты
* установил phpstan и поправил ошибки в коде после его анализа
* появился ci c прогоном тестов и phpstan

# 3.1.0
Петр Бекренев
* Убрал валидацию типов и объемов медиа файлов
* Добавил в инструкцию по установке бандла как настроить требования к медиа файлам

# 3.0.3
Александр Володин
* Добавил фильтрацию по локали при поиске моделей
* Оптимизировал запрос на поиск моделей (Ранее для каждого фильтра, использовался отдельный join)

# 3.0.2
Александр Володин
* Пофиксил миграции, запуск фикстур и тестов
* Пофиксил поднятие контейнеров и Taskfile
* Теперь можно запускать тесты: `task setup` - поднятие контейнеров и миграций, `task test` - запуск тестов

# 3.0.1
Любомир Соловьев
* Убрал проверку доступа API: `/api/v1/cms/get-templates`.

# 3.0.0
Александр Володин
* Удалил сущности `Page`, `PageSeoData`, `PageOpenGraphData`, `PageCustomMetaTag`. Удалил все сервисы использующие эти сущности.
* Удалил API: `/api/v1/cms/get-page` , `/api/v1/cms/get-components`, `/api/v1/cms/save-page-components`
* Удалил клонирование и получение компонентов страниц `/admin/page-component/form` и `/admin/page/clone`
* Удалил интерфейсы компонентов форм `ComponentTypeInterface` и `ComponentPreviewInterface`, и связанные с ними сервисы.
* Переименовал `PageComponent` в `Component`, включая связанные сервисы.

Рекомендации при обновлении:
* Необходимо удалить сервис, которые использует интерфейс `PageFrontendUrlResolverInterface`.
* Проверить проект на вхождение `PageComponent` и `pageComponent`, и в случае использования заменить на `Component` и `component` соответственно.
* Накатить миграцию `src/Infrastructure/Doctrine/Migration/Version20220419120520.php`
* Установить `phpstan` или `psalm` и проверить проект на использование несуществующих сервисов.

# 2.2.14
Александр Володин
* Добавил сервис генерации ссылки на CMS Editor для готовых компонентов: TemplateCmsEditorFrontendUrlResolver
* Добавил isPublished у готовых компонентов в ендпоинтах /api/v1/cms/get-model-components и /api/v1/cms/get-template-components
* Добавил ендпоинт для сохранения компонентов готового компонента /api/v1/cms/save-template-components

# 2.2.13
Петр Бекренев
* проверка прав доступа к cms-editor оформлена в отдельный доменный сервис
* поддержка проверки доступа к cms-editor для symfony 5 

# 2.2.12
Александр Володин
* сделал чекбокс `Компонент активен` не обязательным для заполнения

# 2.2.11
Евгений Перин
* пофиксил вывод компонентов после сломанного компонента

# 2.2.10
Александр Володин
* пофиксил поиск по значению у FieldRepository, сделал более типизированным

# 2.2.9
Александр Володин
* задал значения по умолчанию для интерфейса ModelRepositoryInterface::filter
* фикс баги в сервисе генерации публичных ссылок ModelFrontendUrlResolverService (для null значений у Field возникает ошибка)

# 2.2.8
Евгений Перин
* фикс поиска по моделям

# 2.2.7
Александр Володин
* фикс сервиса PublishedComponentsGenerator, возникала ошибка «Warning: Undefined array key ""»

# 2.2.6
Петр Бекренев
* добавил хук setNullOnCreate

# 2.2.5
Александр Володин
* разрешил использование в проектах с php 8
* убрал групповое удаление
* фикс в RedirectCrudController

# 2.2.4
Петр Бекренев
* Добавил в API метод для сохранения компонентов по id модели

# 2.2.3
Петр Бекренев
* Добавил в API метод для получения компонентов по id модели

# 2.2.2
Александр Володин
* Добавил api-тест для /api/v1/cms/get-page
* Добавил фикстуры для File и Redirect

# 2.2.1
Петр Бекренев
* Для моделей добавлена поддержка отображения ссылки на CmsEditor 

# 2.2.0
Александр Володин, Евгений Перин
* Появился сервис `ModelsConfiguration` для удобной работы с конфигом Модели
* Появился сервис `LocaleConfiguration` для доступа к данным конфига локалей и `LocaleResolver` для получения текущей локали
* У полей появились доп. настройки: `required`, `cloneable`, `hide_on_form`, `hooks`.
* Теперь модели можно клонировать, нужно у модели указать `cloneable: true`
* Появился сервис для создания ссылки на публичную версию страницы

Обновил документацию по [Моделям](https://gitlab.skyeng.link/skyeng/marketing-cms-bundle/-/blob/master/docs/MODEL.md)

ВНИМАНИЕ: Интерфейс `EasyAdminFieldFactoryInterface::create` изменился, проверьте использование в своем проекте. Теперь вместо `array $fieldDefinition` он принимает `FieldConfig $fieldConfig`, который содержит те же данные, что и массив до этого.

# 2.1.3
Петр Бекренев
* Рефакторинг LocalizedModelAsArrayAssembler - вынес получение значения поля для каждого типа в соответсвующие классы

# 2.1.2
Петр Бекренев
* Добавил поддержку symfony/serializer версии 5.3

# 2.1.1
Евгений Работнев
* Исправил проблему: бросается warning: `Template component has no template in data` у обычных компонентов в методе PublishedComponentsGenerator::getComponents

# 2.1.0
Александр Володин, Евгений Перин, Олег Скляров

[C0-4492] CRUD Api для CMS Editor.

* /api/v1/cms/get-components - Получение компонентов
* /api/v1/cms/get-templates - Получение списка готовых компонентов
* /api/v1/cms/get-template-components - Получение компонентов готового компонета
* /api/v1/cms/add-media-file - Добавление медиа-файла
* /api/v1/cms/remove-media-files - Удаление медиа-файлов

Подробнее о редакторе можно почитать в документации по [CMS Editor](https://gitlab.skyeng.link/skyeng/marketing-cms-bundle/-/blob/master/docs/CMS_EDITOR.md).

ВНИМАНИЕ: Для плавного перехода нужно поменять использование этой константы `TemplateComponentType::NAME` на `PageComponentName::TEMPLATE_NAME`, либо использовать метод `isTemplateName()` у ValueObject `PageComponentName`.

# 2.0.2
Александр Володин
* [C0-5635] фикс конфликта: временно добавил у PageSeoData необязательное поле is_schema_org_enabled. 
* После обновления надо накатить эту миграцию /src/Infrastructure/Doctrine/Migration/Version20220119162549.php .
* Добавление этого рудиментного поля вызвано необходимостью провести обновление у skyeng-frontface-backend c версии 1.6 на 2-ую. В версии 1.6 данное поле присутствует и им пользуется seo специалисты.
* Кроме этого поля, спрятал чекбокс isIncludedInSitemap, так как им пользуются только в skysmart. Для его отображения можно переопределить PageCrudController.

# 2.0.1
Олег Скляров
* [С0-5289] Возможность создавать страницу, где каждый компонент вложен в какую-либо группу

# 2.0.0
Андреев Павел
* [I18N-17] Skyeng\MarketingCmsBundle\Application\Cms\Model\Dto\ModelRequest замена конструктора на статический с именем create
* [I18N-17] Skyeng\MarketingCmsBundle\Application\Cms\Model\Dto\ModelRequest переименовано поле uuid => id
* [I18N-17] Skyeng\MarketingCmsBundle\Application\Cms\Model\Dto\ModelsRequest замена конструктора на статический с именем create
* [I18N-17] Skyeng\MarketingCmsBundle\Application\Cms\Model\Dto\ModelsRequest переименовано поле sort => sorts
* [I18N-17] Переименованы query параметры роута `/api/v1/cms/get-models` filter => filters, sort => sorts
* [I18N-17] Конфигурация choices теперь часть options. [Пример](https://gitlab.skyeng.link/skyeng/marketing-cms-bundle/blob/d64c1361e7c4067606fced51001e69ac8def7491/src/Resources/config/default/marketing_cms.yaml#L43-L50)

# 1.8.3
Евгений Работнев
* [SM-4970] Сортировка позиции компонентов

# 1.8.2
Ильдар Джакпаров, Олег Скляров
* [C0-4927] Сортировка позиции компонентов страницы на фронте. Отключена валидация позиции на бэке.

# 1.8.1
Андреев Павел
* [hotfix] Каскадное удаление полей при удалении модели

# 1.8.0
Андреев Павел
* [I18N-5] Функционал универсальных моделей (aka headless cms). Требуется таблицы cms_model, cms_field и новое поле field_id в таблице cms_page_component

# 1.7.1
Александр Володин
* [hotfix] Убрал не использующийся isSchemaOrgEnabled в конструкторе, из-за которого возникала ошибка

# 1.7.0
Данияль Ибрагим
* [C0-4528] Добавил isIncludedInSitemap у PageSeoData. Внимание! В версии нет изменений 1.6.0, подробнее о проблеме в треде https://skyeng.slack.com/archives/GPH5FG04Q/p1630404534039800

# 1.6.0
Александр Володин
* [C0-4491] Добавил isSchemaOrgEnabled у PageSeoData и фикс inversed-by у PageCustomMetaTag

# 1.5.0
Александр Володин
* [C0-4377] Добавил createdAt у Redirect, File, MediaFile, Template и сделал по ним сортировку по-умолчанию

# 1.4.13
Александр Володин
* [C0-4180] Увеличил кол-во mime-type'ов у media-файлов

# 1.4.12
Андрей Ердиков
* [C0-4111] Добавлено поле lastModified, фикс обновления для поля updatedAt

# 1.4.11
Андрей Ердиков
* [C0-3473] Добавлено новое поле canonicalUrl для страниц, добавлено обновление для publishedAt

# 1.4.10
Алексей Бабкин
* [C0-3956] Добавлены в админку html и xml content-type для файлов

# 1.4.8
Алексей Бабкин
* [C0-3956] Добавлен html и xml content-type для файлов

# 1.4.7
Андрей Ердиков
* [C0-3856] Добавлено клонирование готовых компонентов

# 1.4.6
Андрей Ердиков
* [C0-3854] Добавлен вывод превью для готовых компонентов + поправлены стили

# 1.4.5
Андрей Ердиков
* [C0-3840] Фикс проблемы с сохранением вложенных элементов в компонентах

# 1.4.4
Андрей Ердиков
* [C0-3817] Добавлено клонирование страниц

# 1.4.3
Александр Володин
* [C0-3760] Фикс подгрузки компонентов с коллекциями

# 1.4.2
Андрей Ердиков
* [C0-3777] Добавлена команда для генерации компонентов

# 1.4.1
Андрей Ердиков
* [C0-3765] Добавлены превью для компонентов

# 1.4.0
Андрей Ердиков
* [C0-3710] Добавлены готовые компоненты

# 1.3.0
Андрей Ердиков
* [C0-3648] Добавлены динамические компоненты и возможность их расширения

# 1.2.1
Андрей Ердиков
* [C0-3562] Фикс бага с событием при котором проставляется тип загружаемого медиа файла

# 1.2.0
Андрей Ердиков
* [C0-3455] Добавили медиа библиотеку для загрузки файлов (изображения, видео, pdf)

# 1.1.1 
Александр Володин
* [C0-3464] Фиксы миграций и api-доки

# 1.1.0
Андрей Ердиков
* [C0-3357] Добавил возможность создавать страницы

# 1.0.3
Сергей Романов
* [C0-3442] Добавить возможность создавать файлы с вложенными урлами

# 1.0.2
Сергей Романов
* [C0-3372] Добавил редиректам валидацию на одинаковое значение

# 1.0.1
Сергей Романов
* [C0-3372] Удалил фикстуры, чтобы они не афектили основной проект

# 1.0.0
Сергей Романов
* Начальный функционал
* Статические файлы
* Редиректы
