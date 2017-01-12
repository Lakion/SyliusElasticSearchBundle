<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query;

use Lakion\SyliusElasticSearchBundle\Exception\MissingQueryParameterException;
use ONGR\ElasticsearchDSL\Query\NestedQuery;
use ONGR\ElasticsearchDSL\Query\RangeQuery;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductInPriceRangeQueryFactory implements QueryFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(array $parameters = [])
    {
        $this->assertParameters($parameters);

        $graterThan = $parameters['product_price_range']['grater_than'];
        $lessThan = $parameters['product_price_range']['less_than'];

        return new NestedQuery(
            'variants',
            new NestedQuery(
                'variants.channelPricings',
                new RangeQuery('variants.channelPricings.price', ['gte' => $graterThan, 'lte' => $lessThan])
            )
        );
    }

    /**
     * @param array $parameters
     *
     * @throws MissingQueryParameterException
     */
    private function assertParameters(array $parameters)
    {
        if (!array_key_exists('product_price_range', $parameters)) {
            throw new MissingQueryParameterException('product_price_range', get_class($this));
        }

        if (!array_key_exists('grater_than', $parameters['product_price_range'])) {
            throw new MissingQueryParameterException('product_price_range.grater_than', get_class($this));
        }

        if (!array_key_exists('less_than', $parameters['product_price_range'])) {
            throw new MissingQueryParameterException('product_price_range.less_than', get_class($this));
        }
    }
}
