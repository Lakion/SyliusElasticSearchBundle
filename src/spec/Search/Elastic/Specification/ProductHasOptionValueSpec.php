<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Specification;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Specification\ProductHasOptionValue;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Specification\SpecificationInterface;
use ONGR\ElasticsearchDSL\Query\NestedQuery;
use ONGR\ElasticsearchDSL\Query\TermQuery;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductHasOptionValueSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ProductHasOptionValue::class);
    }

    function it_is_specification()
    {
        $this->shouldImplement(SpecificationInterface::class);
    }

    function it_builds_filtering_query_for_product_option()
    {
        $criteria = Criteria::fromQueryParameters('product', ['product_option_code' => 't-shirt-color']);
        $nestedQuery = new NestedQuery('variants', new NestedQuery('variants.optionValues', new TermQuery('variants.optionValues.code', 't-shirt-color')));

        $this->getQueryFor($criteria)->shouldBeLike($nestedQuery);
    }

    function it_supports_only_specific_criteria()
    {
        $this->satisfies(
            Criteria::fromQueryParameters('product', ['product_option_code' => 't-shirt-color'])
        )->shouldReturn(true);

        $this->satisfies(
            Criteria::fromQueryParameters('product', ['product_attribute' => 't-shirt-color', 'product_attribute_value' => 't-shirt-color-red'])
        )->shouldReturn(false);
    }

    function it_has_parameter_key()
    {
        $this->getParameterKey()->shouldReturn('product_option_code');
    }
}
