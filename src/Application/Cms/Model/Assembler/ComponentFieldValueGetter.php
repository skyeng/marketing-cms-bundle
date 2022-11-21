<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Model\Assembler;

use Doctrine\Common\Collections\Collection;
use Skyeng\MarketingCmsBundle\Application\Cms\PublishedComponentsGenerator;
use Skyeng\MarketingCmsBundle\Domain\Entity\AbstractComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\Field;

final class ComponentFieldValueGetter implements FieldValueGetterInterface
{
    public function __construct(
        private FieldValueGetterInterface $next,
        private PublishedComponentsGenerator $publishedComponentsGenerator
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getFieldValue(Field $field, ?string $locale = null)
    {
        $value = $field->getValue();

        if (!$value instanceof Collection) {
            return $this->next->getFieldValue($field, $locale);
        }

        $components = [];
        $order = 0;

        /** @var AbstractComponent $component */
        foreach ($this->publishedComponentsGenerator->getComponents($field) as $component) {
            $components[] = [
                'name' => $component->getName()->getValue(),
                'order' => ++$order,
                'data' => $component->getData(),
            ];
        }

        return $components;
    }
}
