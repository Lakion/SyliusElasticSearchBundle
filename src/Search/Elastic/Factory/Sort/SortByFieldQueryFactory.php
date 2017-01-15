<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Sort;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Ordering;
use ONGR\ElasticsearchDSL\Sort\FieldSort;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class SortByFieldQueryFactory implements SortFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(Ordering $ordering)
    {
        return new FieldSort('raw_' . $ordering->getField(), $ordering->getDirection());
    }
}
