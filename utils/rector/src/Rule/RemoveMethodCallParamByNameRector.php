<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Utils\Rector\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PHPStan\Type\ObjectType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Skyeng\MarketingCmsBundle\Utils\Rector\ValueObject\RemoveMethodCallParamByName;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

class RemoveMethodCallParamByNameRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var RemoveMethodCallParamByName[]
     */
    private array $removeMethodCallParamsByName = [];

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, StaticCall::class];
    }

    /**
     * @param MethodCall|StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        $hasChanged = false;

        $keys = [];

        foreach ($this->removeMethodCallParamsByName as $removeMethodCallParam) {
            if (!$this->isName($node->name, $removeMethodCallParam->getMethodName())) {
                continue;
            }

            if (!$this->isCallerObjectType($node, $removeMethodCallParam)) {
                continue;
            }

            foreach ($node->args as $key => $arg) {
                $variable = $arg->value;

                if (!$variable instanceof Node\Expr\Variable || $variable->name !== $removeMethodCallParam->getArgumentName()) {
                    continue;
                }

                if (!$this->isCallerArgumentType($variable, $removeMethodCallParam)) {
                    continue;
                }

                $keys[] = $key;
            }
        }

        foreach ($keys as $key) {
            unset($node->args[$key]);
            $hasChanged = true;
        }

        if (!$hasChanged) {
            return null;
        }

        return $node;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove parameter of method call', []);
    }

    public function configure(array $configuration): void
    {
        Assert::allIsInstanceOf($configuration, RemoveMethodCallParamByName::class);
        $this->removeMethodCallParamsByName = $configuration;
    }

    /**
     * @param StaticCall|MethodCall $call
     */
    private function isCallerObjectType($call, RemoveMethodCallParamByName $removeMethodCallParamByName): bool
    {
        if ($call instanceof MethodCall) {
            return $this->isObjectType($call->var, $removeMethodCallParamByName->getObjectType());
        }

        return $this->isObjectType($call->class, $removeMethodCallParamByName->getObjectType());
    }

    private function isCallerArgumentType(Variable $variable, RemoveMethodCallParamByName $removeMethodCallParamByName): bool
    {
        $expectedArgumentType = $removeMethodCallParamByName->getArgumentType();

        if ($expectedArgumentType instanceof ObjectType) {
            if ($this->isObjectType($variable, $expectedArgumentType)) {
                return true;
            }

            return false;
        }

        $type = get_class($removeMethodCallParamByName->getArgumentType());

        if ($this->getType($variable) instanceof $type) {
            return true;
        }

        return false;
    }
}
