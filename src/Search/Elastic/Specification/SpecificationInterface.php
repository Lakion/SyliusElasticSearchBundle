<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Specification;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use ONGR\ElasticsearchDSL\BuilderInterface;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
interface SpecificationInterface
{
    /**
     * @param Criteria $criteria
     *
     * @return BuilderInterface
     */
    public function getQueryFor(Criteria $criteria);

    /**
     * @param Criteria $criteria
     *
     * @return bool
     */
    public function satisfies(Criteria $criteria);

    /**
     * @return string
     */
    public function getParameterKey();
}
