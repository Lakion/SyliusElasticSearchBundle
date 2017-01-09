<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Query;

use Elastica\QueryBuilder\DSL;
use Lakion\SyliusElasticSearchBundle\Exception\MissingQueryParameterException;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
interface QueryInterface
{
    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters);

    /**
     * @return DSL
     *
     * @throws MissingQueryParameterException
     */
    public function create();
}
