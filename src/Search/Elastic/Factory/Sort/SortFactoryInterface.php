<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Sort;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Ordering;
use ONGR\ElasticsearchDSL\BuilderInterface;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
interface SortFactoryInterface
{
    /**
     * @param Ordering $ordering
     *
     * @return BuilderInterface
     */
    public function create(Ordering $ordering);
}
