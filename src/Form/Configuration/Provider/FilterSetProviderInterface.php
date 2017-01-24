<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Form\Configuration\Provider;

use Lakion\SyliusElasticSearchBundle\Exception\FilterSetConfigurationNotFoundException;
use Lakion\SyliusElasticSearchBundle\Form\Configuration\FilterSet;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
interface FilterSetProviderInterface
{
    /**
     * @param string $filterSetName
     *
     * @return FilterSet
     *
     * @throws FilterSetConfigurationNotFoundException
     */
    public function getFilterSetConfiguration($filterSetName);
}
