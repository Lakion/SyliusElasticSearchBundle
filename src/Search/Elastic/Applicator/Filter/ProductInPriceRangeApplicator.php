<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Filter;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\SearchCriteriaApplicatorInterface;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query\QueryFactoryInterface;
use ONGR\ElasticsearchDSL\Query\BoolQuery;
use ONGR\ElasticsearchDSL\Search;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductInPriceRangeApplicator implements SearchCriteriaApplicatorInterface
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
    public function apply(Criteria $criteria, Search $search)
    {
        $search->addFilter($this->productInPriceRangeQueryFactory->create($criteria->getFiltering()->getFields()), BoolQuery::MUST);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Criteria $criteria)
    {
        return $this->keyExists($criteria->getFiltering()) && $this->valueNotNull($criteria->getFiltering());
    }

    /**
     * @param Filtering $filtering
     *
     * @return bool
     */
    private function keyExists(Filtering $filtering)
    {
        $filteringFields = $filtering->getFields();

        return
            array_key_exists('product_price_range', $filteringFields) &&
            array_key_exists('grater_than', $filteringFields['product_price_range']) &&
            array_key_exists('less_than', $filteringFields['product_price_range']);
    }

    /**
     * @param Filtering $filtering
     *
     * @return bool
     */
    private function valueNotNull(Filtering $filtering)
    {
        $filteringFields = $filtering->getFields();

        return
            null != $filteringFields['product_price_range']['grater_than'] &&
            null != $filteringFields['product_price_range']['less_than'];
    }
}
