<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Utils\Rector\Rule;

use PhpParser\Node;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

class ReplaceStringWithConstRector extends AbstractRector implements ConfigurableRectorInterface
{
    public const REPLACEMENT_LIST = 'replacement_list';

    /**
     * @var array<array<string>>
     */
    private array $replacementList = [];

    /**
     * @param array<string, array<string>> $configuration
     */
    public function configure(array $configuration): void
    {
        $this->replacementList = $configuration[self::REPLACEMENT_LIST] ?? [];

        Assert::notEmpty($this->replacementList);

        foreach ($this->replacementList as [$searchString, $replacementClass, $replacementConst]) {
            Assert::string($searchString);
            Assert::notEq($searchString, '', 'searchString should not be empty');
            Assert::string($replacementClass);
            Assert::notEq($replacementClass, '', 'replacementClass should not be empty');
            Assert::string($replacementConst);
            Assert::notEq($replacementConst, '', 'replacementConst should not be empty');
        }
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Scalar\String_::class];
    }

    /**
     * @param Node\Scalar\String_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $parent = $node->getAttribute(AttributeKey::PARENT_NODE);

        if ($parent instanceof Node\Const_) {
            return null;
        }

        foreach ($this->replacementList as [$searchString, $replacementClass, $replacementConst]) {
            if ($node->value !== $searchString) {
                continue;
            }

            return $this->nodeFactory->createClassConstFetch($replacementClass, $replacementConst);
        }

        return null;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace string with const', []);
    }
}
