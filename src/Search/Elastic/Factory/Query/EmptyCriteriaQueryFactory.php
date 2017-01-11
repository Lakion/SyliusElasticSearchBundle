<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query;

use ONGR\ElasticsearchDSL\Query\MatchAllQuery;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class EmptyCriteriaQueryFactory implements QueryFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(array $parameters = [])
    {
        return new MatchAllQuery();
    }
}
