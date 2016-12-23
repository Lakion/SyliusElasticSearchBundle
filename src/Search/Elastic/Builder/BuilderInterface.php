<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Builder;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use ONGR\ElasticsearchDSL\Search;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
interface BuilderInterface
{
    /**
     * @param Criteria $criteria
     *
     * @return bool
     */
    public function supports(Criteria $criteria);

    /**
     * @param Criteria $criteria
     * @param Search $search
     */
    public function build(Criteria $criteria, Search $search);
}
