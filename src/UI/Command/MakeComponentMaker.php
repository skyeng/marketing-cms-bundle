<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Command;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Filesystem\Filesystem;

class MakeComponentMaker extends AbstractMaker
{
    /**
     * @var string
     */
    private $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public static function getCommandName(): string
    {
        return 'make:marketing-cms:api';
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
            ->addArgument('title', InputArgument::REQUIRED, 'Заголовок компонента для админки')
            ->setHelp('');
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $name = trim((string)$input->getArgument('name'), '-');
        $title = (string)$input->getArgument('title');

        if (
            !preg_match(
                '/^[a-z-]*$/',
                $name
            )
        ) {
            throw new \RuntimeException('Неправильный формат названия компонента');
        }

        $formattedName = $this->getFormattedName($name);

        $data = [
            '_CG_FORMATTED_NAME_' => $formattedName,
            '_CG_NAME_' => $name,
            '_CG_TITLE_' => $title,
            '_CG_APPROOT_' => 'App',
        ];

        $templates = [
            'ComponentType.php' => sprintf(
                '%s/src/Infrastructure/Symfony/Form/ComponentTypes/%sComponentType.php',
                $this->projectDir,
                $formattedName,
            ),
        ];

        $filesystem = new Filesystem();
        foreach ($templates as $template => $path) {
            $io->writeln($path);

            clearstatcache();
            $content = $this->getContent($template, $data);
            $dir = dirname($path);
            if (!$filesystem->exists($dir)) {
                $filesystem->mkdir($dir);
            }

            if ($filesystem->exists($path)) {
                $filesystem->remove($path);
            }
            $filesystem->touch($path);
            $filesystem->appendToFile($path, $content);
        }
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
        $dependencies->addClassDependency(Command::class, 'console');
    }

    private function getContent(string $file, array $data): string
    {
        $path = __DIR__ . '/../../Resources/config/code-templates/' . $file;
        $content = file_get_contents($path);
        return str_replace(array_keys($data), array_values($data), $content);
    }

    private function getFormattedName(string $name): string
    {
        $formattedName = array_map(function ($nameChunk) {
            return ucfirst($nameChunk);
        }, explode('-', $name));

        return implode('', $formattedName);
    }
}
