<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('lakion_sylius_elastic_search');

        $this->buildFilterSetNode($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $rootNode
     */
    private function buildFilterSetNode(ArrayNodeDefinition $rootNode)
    {
        $filterSetNode = $rootNode
            ->children()
                ->arrayNode('filter_sets')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
            ->validate()
                ->ifEmpty()
                ->thenInvalid('"%s" cannot be empty')
            ->end()
        ;

        $this->buildFiltersNode($filterSetNode);
    }

    /**
     * @param ArrayNodeDefinition $filterSetNode
     */
    private function buildFiltersNode(ArrayNodeDefinition $filterSetNode)
    {
        $filtersNode = $filterSetNode
            ->children()
                ->arrayNode('filters')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
            ->validate()
                ->ifEmpty()
                ->thenInvalid('"%s"" cannot be empty')
            ->end()
        ;

        $this->buildFilterNode($filtersNode);
    }

    /**
     * @param ArrayNodeDefinition $filtersNode
     */
    private function buildFilterNode(ArrayNodeDefinition $filtersNode)
    {
        $filtersNode
            ->children()
                ->scalarNode('type')->cannotBeEmpty()->end()
                ->arrayNode('options')
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')
        ;
    }
}
