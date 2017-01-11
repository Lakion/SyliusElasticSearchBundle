<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class RegisterSearchCriteriaApplicatorPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('lakion_sylius_elastic_search.search.elastic_engine')) {
            return;
        }

        foreach ($container->findTaggedServiceIds('search_criteria_applicator') as $taggedServiceId => $taggedServiceConfig) {
            $engineDefinition = $container->getDefinition('lakion_sylius_elastic_search.search.elastic_engine');
            $engineDefinition->addMethodCall('addSearchCriteriaApplicator', [new Reference($taggedServiceId)]);
        }
    }
}
