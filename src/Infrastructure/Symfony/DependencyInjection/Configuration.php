<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public const ROOT_GROUP = 'root';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('marketing_cms');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('editor')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('show_editor_link')->defaultFalse()->end()
                        ->arrayNode('security')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')->defaultTrue()->end()
                                ->arrayNode('roles')->defaultValue([])
                                    ->scalarPrototype()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('locales')
                    ->scalarPrototype()->end()
                ->end()
                ->scalarNode('default_locale')->defaultValue('ru')->end()
                ->arrayNode('models')
                    ->useAttributeAsKey('name', false)
                    ->arrayPrototype()
                        ->children()
                            ->booleanNode('cloneable')->defaultFalse()->end()
                            ->scalarNode('patternUrl')->defaultValue('')->end()
                            ->scalarNode('name')->end()
                            ->scalarNode('label')->end()
                            ->arrayNode('fields')
                                ->useAttributeAsKey('name', false)
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('name')->end()
                                        ->scalarNode('type')->end()
                                        ->scalarNode('label')->end()
                                        ->booleanNode('localized')->defaultFalse()->end()
                                        ->booleanNode('required')->defaultTrue()->end()
                                        ->booleanNode('cloneable')->defaultTrue()->end()
                                        ->booleanNode('hide_on_index')->defaultFalse()->end()
                                        ->booleanNode('hide_on_form')->defaultFalse()->end()
                                        ->arrayNode('hooks')->scalarPrototype()->end()->end()
                                        ->scalarNode('group')->defaultValue(self::ROOT_GROUP)->end()
                                        ->arrayNode('options')
                                            ->children()
                                                ->arrayNode('choices')
                                                    ->arrayPrototype()
                                                        ->children()
                                                            ->scalarNode('label')->end()
                                                            ->scalarNode('value')->end()
                                                        ->end()
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
