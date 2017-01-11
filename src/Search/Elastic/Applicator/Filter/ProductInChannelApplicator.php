<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Filter;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\SearchCriteriaApplicatorInterface;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query\QueryFactoryInterface;
use ONGR\ElasticsearchDSL\Query\BoolQuery;
use ONGR\ElasticsearchDSL\Search;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductInChannelApplicator implements SearchCriteriaApplicatorInterface
{
    /**
     * @var QueryFactoryInterface
     */
    private $productInChannelQueryFactory;

    /**
     * @param QueryFactoryInterface $productInChannelQueryFactory
     */
    public function __construct(QueryFactoryInterface $productInChannelQueryFactory)
    {
        $this->productInChannelQueryFactory = $productInChannelQueryFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Criteria $criteria, Search $search)
    {
        $search->addFilter($this->productInChannelQueryFactory->create($criteria->getFiltering()->getFields()), BoolQuery::MUST);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Criteria $criteria)
    {
        return
            array_key_exists('channel_code', $criteria->getFiltering()->getFields()) &&
            null != $criteria->getFiltering()->getFields()['channel_code'];
    }
}
