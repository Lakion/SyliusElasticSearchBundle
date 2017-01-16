<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Query;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\SearchCriteriaApplicatorInterface;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query\QueryFactoryInterface;
use ONGR\ElasticsearchDSL\Search;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class MatchProductByNameApplicator implements SearchCriteriaApplicatorInterface
{
    /**
     * @var QueryFactoryInterface
     */
    private $matchProductNameQueryFactory;

    /**
     * @param QueryFactoryInterface $matchProductNameQueryFactory
     */
    public function __construct(QueryFactoryInterface $matchProductNameQueryFactory)
    {
        $this->matchProductNameQueryFactory = $matchProductNameQueryFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Criteria $criteria, Search $search)
    {
        $search->addQuery($this->matchProductNameQueryFactory->create($criteria->getFiltering()->getFields()));
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Criteria $criteria)
    {
        return
            array_key_exists('search', $criteria->getFiltering()->getFields()) &&
            null != $criteria->getFiltering()->getFields()['search']
        ;
    }
}
