<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Sort;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Ordering;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\SearchCriteriaApplicator;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\SearchCriteriaApplicatorInterface;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Sort\SortFactoryInterface;
use ONGR\ElasticsearchDSL\Search;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class SortByFieldApplicator extends SearchCriteriaApplicator
{
    /**
     * @var SortFactoryInterface
     */
    private $sortByFieldQueryFactory;

    /**
     * @param SortFactoryInterface $sortByFieldQueryFactory
     */
    public function __construct(SortFactoryInterface $sortByFieldQueryFactory)
    {
        $this->sortByFieldQueryFactory = $sortByFieldQueryFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function applyOrdering(Ordering $ordering, Search $search)
    {
        $search->addSort($this->sortByFieldQueryFactory->create($ordering));
    }
}
