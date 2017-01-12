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
final class ProductHasMultipleOptionCodesApplicator implements SearchCriteriaApplicatorInterface
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
     * {@inheritdoc}
     */
    public function apply(Criteria $criteria, Search $search)
    {
        $productOptionCodes = explode('+', $criteria->getFiltering()->getFields()['product_option_code']);

        foreach ($productOptionCodes as $productOptionCode) {
            $search->addFilter(
                $this->productHasOptionCodeQueryFactory->create(['option_value_code' => $productOptionCode]),
                BoolQuery::SHOULD
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Criteria $criteria)
    {
        $fields = $criteria->getFiltering()->getFields();

        return array_key_exists('product_option_code', $criteria->getFiltering()->getFields()) && '' !== $fields['product_option_code'];
    }
}
