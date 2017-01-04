<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Specification;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use ONGR\ElasticsearchDSL\Query\MatchAllQuery;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class EmptyCriteria implements SpecificationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getQueryFor(Criteria $criteria)
    {
        return new MatchAllQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function satisfies(Criteria $criteria)
    {
        return empty($criteria->getFiltering()->getFields());
    }

    /**
     * {@inheritdoc}
     */
    public function getParameterKey()
    {
        return null;
    }
}
