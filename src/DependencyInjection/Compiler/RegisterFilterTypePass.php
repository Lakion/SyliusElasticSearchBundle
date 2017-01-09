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
final class RegisterFilterTypePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('lakion_sylius_elastic_search.form_registry.filters')) {
            return;
        }

        $registry = $container->getDefinition('lakion_sylius_elastic_search.form_registry.filters');

        foreach ($container->findTaggedServiceIds('filter.type') as $filterTypeServiceId => $filterTypeTag)
        {
            if (!isset($filterTypeTag[0]['type'])) {
                throw new \InvalidArgumentException(
                    sprintf('Filter type must have type argument configured! "%s"', $filterTypeServiceId)
                );
            }

            $registry->addMethodCall('add', [
                'default',
                $filterTypeTag[0]['type'],
                $container->getDefinition($filterTypeServiceId)->getClass()
            ]);
        }
    }
}
