<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query;

use Lakion\SyliusElasticSearchBundle\Exception\MissingQueryParameterException;
use ONGR\ElasticsearchDSL\Query\NestedQuery;
use ONGR\ElasticsearchDSL\Query\TermQuery;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductHasOptionCode implements QueryFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create($parameters = [])
    {
        if (!isset($parameters['option_value_code'])) {
            throw new MissingQueryParameterException('option_value_code', get_class($this));
        }

        return
            new NestedQuery(
                'variants',
                    new NestedQuery(
                        'variants.optionValues',
                            new TermQuery('variants.optionValues.code', $parameters['option_value_code'])
                    )
            );
    }
}
