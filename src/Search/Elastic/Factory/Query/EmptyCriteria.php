<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query;

use ONGR\ElasticsearchDSL\Query\MatchAllQuery;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class EmptyCriteria implements QueryFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create($parameters = [])
    {
        return new MatchAllQuery();
    }
}
