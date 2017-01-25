<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Filter;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering\ProductHasOptionCodesFilter;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\SearchCriteriaApplicator;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query\QueryFactoryInterface;
use ONGR\ElasticsearchDSL\Query\BoolQuery;
use ONGR\ElasticsearchDSL\Search;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductHasMultipleOptionCodesApplicator extends SearchCriteriaApplicator
{
    /**
     * @var QueryFactoryInterface
     */
    private $productHasOptionCodeQueryFactory;

    /**
     * {@inheritdoc}
     */
    public function __construct(QueryFactoryInterface $productHasOptionCodeQueryFactory)
    {
        $this->productHasOptionCodeQueryFactory = $productHasOptionCodeQueryFactory;
    }

    /**
     * @param ProductHasOptionCodesFilter $codesFilter
     * @param Search $search
     */
    public function applyProductHasOptionCodesFilter(ProductHasOptionCodesFilter $codesFilter, Search $search)
    {
        foreach ($codesFilter->getCodes() as $code) {
            $search->addFilter(
                $this->productHasOptionCodeQueryFactory->create(['option_value_code' => $code]),
                BoolQuery::SHOULD
            );
        }
    }
}
