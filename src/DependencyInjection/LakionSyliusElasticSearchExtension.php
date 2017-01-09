<?php

namespace Lakion\SyliusElasticSearchBundle\DependencyInjection;

use Lakion\SyliusElasticSearchBundle\Form\Configuration\FilterScope;
use Lakion\SyliusElasticSearchBundle\Form\Type\FilterScopeType;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

final class LakionSyliusElasticSearchExtension extends AbstractResourceExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.xml');
        $this->createFilterScopeTypes($config, $container);
    }

    /**
     * @param array $config
     */
    private function createFilterScopeTypes(array $config, ContainerBuilder $container)
    {
        $container->setParameter('lakion_sylius_elastic_search.filter_scopes', reset($config)['filter_scopes']);
    }
}
