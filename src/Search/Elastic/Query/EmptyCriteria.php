<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Query;

use ONGR\ElasticsearchDSL\Query\MatchAllQuery;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class EmptyCriteria implements QueryInterface
{
    /**
     * {@inheritdoc}
     */
    public function setParameters(array $parameters)
    {
        throw new \RuntimeException('This query does not have parameters.');
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        return new MatchAllQuery();
    }
}
