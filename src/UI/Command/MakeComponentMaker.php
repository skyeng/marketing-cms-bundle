<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Command;

use InvalidArgumentException;
use ReflectionClass;
use RuntimeException;
use Skyeng\MarketingCmsBundle\UI\Dto\ComponentFormFieldDto;
use Skyeng\MarketingCmsBundle\UI\Service\Helper\FormFieldTypeHelper;
use Skyeng\MarketingCmsBundle\UI\Service\Resolver\ComponentFieldOptionsResolver;
use Skyeng\MarketingCmsBundle\UI\Service\Resolver\ComponentFieldTypeResolver;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;

class MakeComponentMaker extends AbstractMaker
{
    private const TYPE_TEMPLATE_PATH = '/../../Resources/config/code-templates/ComponentType.tpl.php';
    private const TEMPLATE_NAMESPACE_MASK = "App\Infrastructure\Symfony\Form\ComponentTypes\%sComponentType";

    /**
     * @var Generator
     */
    private $generator;

    /**
     * @var ComponentFieldTypeResolver
     */
    private $componentFieldTypeResolver;

    /**
     * @var ComponentFieldOptionsResolver
     */
    private $componentFieldOptionsResolver;

    public function __construct(
        Generator $generator,
        ComponentFieldTypeResolver $componentFieldTypeResolver,
        ComponentFieldOptionsResolver $componentFieldOptionsResolver
    ) {
        $this->generator = $generator;
        $this->componentFieldTypeResolver = $componentFieldTypeResolver;
        $this->componentFieldOptionsResolver = $componentFieldOptionsResolver;
    }

    public static function getCommandName(): string
    {
        return 'make:marketing-cms:component';
    }

    public static function getCommandDescription(): string
    {
        return 'Генерация файлов для компонента';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->setDescription('Генерация файлов для компонента')
            ->addArgument('name', InputArgument::REQUIRED, 'Название компонента')
            ->addArgument('title', InputArgument::REQUIRED, 'Заголовок компонента для админки');
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $name = trim((string)$input->getArgument('name'), '-');
        $title = (string)$input->getArgument('title');

        if (
            !preg_match(
                '/^[a-z0-9-]*$/',
                $name
            )
        ) {
            throw new RuntimeException('Неправильный формат названия компонента');
        }

        /** @var ComponentFormFieldDto[] $fields */
        $fields = [];
        $currentFields = [];

        while (true) {
            $newField = $this->askForNextField($io, $currentFields);

            if (null === $newField) {
                break;
            }

            if ($newField instanceof ComponentFormFieldDto) {
                $currentFields[] = $newField->name;
                $fields[] = $newField;
            }
        }

        $formattedName = $this->getFormattedName($name);

        $data = [
            'formattedName' => $formattedName,
            'name' => $name,
            'title' => $title,
        ];

        $data = array_merge($data, $this->getFormFieldsData($fields));
        $template = sprintf(self::TEMPLATE_NAMESPACE_MASK, $formattedName);

        $this->generator->generateClass(
            $template,
            __DIR__ . self::TYPE_TEMPLATE_PATH,
            $data
        );

        $this->generator->writeChanges();
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
        $dependencies->addClassDependency(Command::class, 'console');
    }

    private function askForNextField(ConsoleStyle $io, array $fields): ?ComponentFormFieldDto
    {
        $io->writeln('');
        $questionText = 'Введите название поля (нажать <return> чтобы прекратить добавление полей)';

        $fieldName = $io->ask($questionText, null, function ($name) use ($fields) {
            if (!$name) {
                return null;
            }

            if (in_array($name, $fields, true)) {
                throw new InvalidArgumentException(sprintf('Поле "%s" уже добавлено', $name));
            }

            return $name;
        });

        if (!$fieldName) {
            return null;
        }

        $defaultType = FormFieldTypeHelper::TYPE_TEXT;
        $allValidTypes = FormFieldTypeHelper::AVAILABLE_TYPES;
        $type = null;

        while (null === $type) {
            $question = new Question('Тип поля (' . implode(',', $allValidTypes) . ')', $defaultType);
            $question->setAutocompleterValues($allValidTypes);
            $type = $io->askQuestion($question);

            if (!in_array($type, $allValidTypes, true)) {
                $io->writeln('Доступные типы: ' . implode(', ', $allValidTypes));
                $io->error(sprintf('Некорректный тип "%s".', $type));

                $type = null;
            }
        }

        $data = ['fieldName' => $fieldName, 'type' => $type];

        if ($io->confirm('Может ли это поле быть пустым? (nullable)', false)) {
            $data['nullable'] = true;
        } else {
            $data['nullable'] = false;
        }

        return new ComponentFormFieldDto($data['fieldName'], $data['type'], $data['nullable']);
    }

    private function getFormattedName(string $name): string
    {
        $formattedName = array_map(static function ($nameChunk) {
            return ucfirst($nameChunk);
        }, explode('-', $name));

        return implode('', $formattedName);
    }

    /**
     * @param ComponentFormFieldDto[] $fields
     */
    private function getFormFieldsData(array $fields): array
    {
        $formDependencies = [];
        $formFields = [];

        foreach ($fields as $field) {
            $type = $field->type;
            $classType = $this->componentFieldTypeResolver->resolveClassTypeByType($type);
            $fieldType = $this->componentFieldTypeResolver->resolveFieldTypeByType($type);
            $options = $this->componentFieldOptionsResolver->resolveOptionsByField($field);

            $formFields[$field->name] = [
                'type' => $fieldType,
                'classType' => (new ReflectionClass($classType))->getShortName(),
                'options' => $options,
            ];

            $formDependencies[$classType] = $classType;
        }

        return [
            'formFields' => $formFields,
            'typeDependencies' => $formDependencies,
        ];
    }
}
