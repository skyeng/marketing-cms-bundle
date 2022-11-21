<?= "<?php\n" ?>

declare(strict_types=1);

namespace <?= $namespace ?>;

use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\ComponentType\ComponentTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
<?php foreach ($typeDependencies as $className): ?>
use <?= $className ?>;
<?php endforeach; ?>

class <?= $formattedName ?>ComponentType extends AbstractType implements DataTransformerInterface, ComponentTypeInterface
{
    private const NAME = '<?= $name ?>';
    private const TITLE = '<?= $title ?>';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
<?php foreach ($formFields as $formField => $typeOptions): ?>
            ->add('<?= $formField ?>', <?= $typeOptions['classType'] ? ($typeOptions['classType'].'::class') : 'null' ?>, [
<?php foreach ($typeOptions['options'] as $option): ?>
                <?= $option.",\n" ?>
<?php endforeach; ?>
            ])
<?php endforeach; ?>
            ;

        $builder->resetViewTransformers();
        $builder->addViewTransformer($this);
    }

    public function transform($value): array
    {
<?php foreach ($formFields as $form_field => $typeOptions): ?>
        $<?= $form_field ?> = $value['<?= $form_field ?>'] ?? null;
<?php endforeach; ?>

        return [
<?php foreach ($formFields as $form_field => $typeOptions): ?>
            '<?= $form_field ?>' => (<?= $typeOptions['type'] ?>)$<?= $form_field ?>,
<?php endforeach; ?>
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
