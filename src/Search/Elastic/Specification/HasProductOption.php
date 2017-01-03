<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Specification;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use ONGR\ElasticsearchDSL\Query\NestedQuery;
use ONGR\ElasticsearchDSL\Query\TermQuery;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class HasProductOption implements SpecificationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getQueryFor(Criteria $criteria)
    {
        return
            new NestedQuery(
                'variants',
                    new NestedQuery(
                        'variants.optionValues',
                            new TermQuery('variants.optionValues.code', $criteria->getFiltering()->getFields()[$this->getParameterKey()])
                    )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function satisfies(Criteria $criteria)
    {
        return array_key_exists($this->getParameterKey(), $criteria->getFiltering()->getFields());
    }

    /**
     * {@inheritdoc}
     */
    public function getParameterKey()
    {
        return 'product_option_code';
    }
}
