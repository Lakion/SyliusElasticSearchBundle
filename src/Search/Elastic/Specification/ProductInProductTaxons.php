<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Specification;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use ONGR\ElasticsearchDSL\Query\NestedQuery;
use ONGR\ElasticsearchDSL\Query\TermQuery;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductInProductTaxons implements SpecificationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getQueryFor(Criteria $criteria)
    {
        return new NestedQuery('productTaxons', new TermQuery('productTaxons.taxon.code', strtolower($criteria->getFiltering()->getFields()[$this->getParameterKey()])));
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
        return 'taxon_code';
    }
}
