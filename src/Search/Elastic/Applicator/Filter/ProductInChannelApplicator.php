<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Filter;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering\ProductInChannelFilter;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\SearchCriteriaApplicator;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query\QueryFactoryInterface;
use ONGR\ElasticsearchDSL\Query\BoolQuery;
use ONGR\ElasticsearchDSL\Search;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductInChannelApplicator extends SearchCriteriaApplicator
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
    public function applyProductInChannelFilter(ProductInChannelFilter $inChannelFilter, Search $search)
    {
        $search->addFilter($this->productInChannelQueryFactory->create(['channel_code' => $inChannelFilter->getChannelCode()]), BoolQuery::MUST);
    }
}
