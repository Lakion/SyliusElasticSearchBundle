<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Query;

use Lakion\SyliusElasticSearchBundle\Exception\MissingQueryParameterException;
use ONGR\ElasticsearchDSL\Query\NestedQuery;
use ONGR\ElasticsearchDSL\Query\TermQuery;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductHasOptionCode implements QueryInterface
{
    /**
     * @var array
     */
    private $parameters = [];

    /**
     * {@inheritdoc}
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        if (!isset($this->parameters['option_value_code'])) {
            throw new MissingQueryParameterException('option_value_code', get_class($this));
        }

        return
            new NestedQuery(
                'variants',
                    new NestedQuery(
                        'variants.optionValues',
                            new TermQuery('variants.optionValues.code', $this->parameters['option_value_code'])
                    )
            );
    }

}
