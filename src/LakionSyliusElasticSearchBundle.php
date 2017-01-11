<?php

namespace Lakion\SyliusElasticSearchBundle;

use Lakion\SyliusElasticSearchBundle\DependencyInjection\Compiler\RegisterFilterTypePass;
use Lakion\SyliusElasticSearchBundle\DependencyInjection\Compiler\RegisterSearchCriteriaApplicatorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class LakionSyliusElasticSearchBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterFilterTypePass());
        $container->addCompilerPass(new RegisterSearchCriteriaApplicatorPass());
    }
}
