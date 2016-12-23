<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Builder;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use ONGR\ElasticsearchDSL\Query\BoolQuery;
use ONGR\ElasticsearchDSL\Query\NestedQuery;
use ONGR\ElasticsearchDSL\Query\TermQuery;
use ONGR\ElasticsearchDSL\Search;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class HasProductOption implements BuilderInterface
{
    /**
     * {@inheritdoc}
     */
    public function build(Criteria $criteria, Search $search)
    {
        $boolQuery = new BoolQuery();
        $boolQuery->add(
            new TermQuery('variants.optionValues.code', $criteria->getFiltering()->getFields()['product_option_code']),
                BoolQuery::MUST
        );

        $search->addFilter(
            new NestedQuery(
                'variants',
                    new NestedQuery(
                        'variants.optionValues',
                        $boolQuery
                    )
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Criteria $criteria)
    {
        $fields = $criteria->getFiltering()->getFields();

        return
            array_key_exists('product_option_code', $fields)
        ;
    }
}
