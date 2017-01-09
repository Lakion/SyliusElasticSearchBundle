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
use ONGR\ElasticsearchDSL\Query\NestedQuery;
use ONGR\ElasticsearchDSL\Query\RangeQuery;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class InPriceRange implements SpecificationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getQueryFor(Criteria $criteria)
    {
        $graterThan = $criteria->getFiltering()->getFields()['product_price_range']['grater_than'];
        $lessThan = $criteria->getFiltering()->getFields()['product_price_range']['less_than'];

        return
            new NestedQuery('variants',
                new NestedQuery(
                    'variants.channelPricings',
                        new RangeQuery('variants.channelPricings.price', ['gte' => $graterThan, 'lte' => $lessThan])
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function satisfies(Criteria $criteria)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameterKey()
    {
        return 'product_price_range_filter';
    }
}
