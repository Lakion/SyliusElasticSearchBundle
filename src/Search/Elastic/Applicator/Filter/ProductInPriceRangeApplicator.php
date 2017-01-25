<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Filter;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering\ProductInPriceRangeFilter;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\SearchCriteriaApplicator;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query\QueryFactoryInterface;
use ONGR\ElasticsearchDSL\Query\BoolQuery;
use ONGR\ElasticsearchDSL\Search;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductInPriceRangeApplicator extends SearchCriteriaApplicator
{
    /**
     * @var QueryFactoryInterface
     */
    private $productInPriceRangeQueryFactory;

    /**
     * @param QueryFactoryInterface $productInPriceRangeQueryFactory
     */
    public function __construct(QueryFactoryInterface $productInPriceRangeQueryFactory)
    {
        $this->productInPriceRangeQueryFactory = $productInPriceRangeQueryFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function applyProductInPriceRangeFilter(ProductInPriceRangeFilter $inPriceRangeFilter, Search $search)
    {
        $search->addFilter(
            $this->productInPriceRangeQueryFactory->create([
                'product_price_range' => [
                    'grater_than' => $inPriceRangeFilter->getGraterThan(),
                    'less_than' => $inPriceRangeFilter->getLessThan()
                ]
            ]),
            BoolQuery::MUST
        );
    }
}
