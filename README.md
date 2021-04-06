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

* Добавляем пункты меню в DashboardController easyadmin-а
    ```php
    use Skyeng\MarketingCmsBundle\Domain\Entity\File;
    use Skyeng\MarketingCmsBundle\Domain\Entity\Redirect;
  
    class DashboardController extends AbstractDashboardController
    {
        public function configureMenuItems(): iterable
        {
            yield MenuItem::section('CMS');
            yield MenuItem::linkToCrud('Файлы', 'fas fa-folder-open', File::class);
            yield MenuItem::linkToCrud('Редиректы', 'fas fa-folder-open', Redirect::class);
        }
    }
    ```
  
### Настройка Медиа библиотеки

* Определить переменную окружения UPLOADS_BASE_URL, она будет использована как uri_prefix для медиа файлов

* По-умолчанию в маппинге vich_uploader для медиа файлов указан upload_destination: 'upload.nfs', его можно переопределить:
    ```yaml
    # config/packages/vich_uploader.yaml
  
    vich_uploader:
      mappings:
        cms_media_files:
          upload_destination: 'uploads.nfs' # Указать свой
    ```

* Также можно переопределить конфиг vich_uploader для prod окружения:
    ```yaml
    # config/packages/prod/vich_uploader.yaml
  
    vich_uploader:
      mappings:
        cms_media_files:
          upload_destination: 'uploads.s3' # Указать свой
    ```

### Добавление своих компонентов для страниц

По-умолчанию доступен только компонент HTML, для добавления собственных компонентов вам нужно:

* Создать собственную форму компонента и реализовать интерфейс Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\ComponentType\ComponentTypeInterface

* В интерфейсе есть два метода: метод getName() возвращает название компонента для использования на клиентской стороне, а getTitle() определяет как будет называться компонент при выборе в админке.

Пример компонента:
```php
namespace App;

use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\ComponentType\ComponentTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CustomComponentType extends AbstractType implements DataTransformerInterface, ComponentTypeInterface
{
    private const NAME = 'custom-component';
    private const TITLE = 'Custom component';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('checkbox', CheckboxType::class);

        $builder->resetViewTransformers();
        $builder->addViewTransformer($this);
    }

    public function transform($value): array
    {
        $title = $value['title'] ?? null;
        $checkbox = $value['checkbox'] ?? null;

        return [
            'title' => (string)$title,
            'checkbox' => (bool)$checkbox,
        ];
    }

    public function reverseTransform($value): array
    {
        return $value;
    }

    public function getTitle(): string
    {
        return self::TITLE;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
```
