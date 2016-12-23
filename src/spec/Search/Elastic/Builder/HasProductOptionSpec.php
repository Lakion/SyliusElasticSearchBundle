<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Builder;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Builder\BuilderInterface;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Builder\HasProductOption;
use ONGR\ElasticsearchDSL\Query\BoolQuery;
use ONGR\ElasticsearchDSL\Query\NestedQuery;
use ONGR\ElasticsearchDSL\Query\TermQuery;
use ONGR\ElasticsearchDSL\Search;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class HasProductOptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(HasProductOption::class);
    }

    function it_is_search_builder()
    {
        $this->shouldImplement(BuilderInterface::class);
    }

    function it_builds_filtering_by_product_option(
        Search $search
    ) {
        $criteria = Criteria::fromQueryParameters('product', ['product_option_code' => 't-shirt-color', 'product_option_name' => 'red']);

        $boolQuery = new BoolQuery();
        $boolQuery->add(new TermQuery('variants.optionValues.code', 't-shirt-color'), BoolQuery::MUST);
        $boolQuery->add(new TermQuery('name', 'red'));

        $nestedQuery = new NestedQuery('variants', new NestedQuery('optionValues', $boolQuery));
        $search->addFilter(Argument::type(NestedQuery::class))->shouldBeCalled();

        $this->build($criteria, $search);
    }

    function it_supports_only_specific_criteria()
    {
        $this->supports(
            Criteria::fromQueryParameters(
                'product',
                    ['product_option_code' => 't-shirt-color', 'product_option_name' => 'red']
            )
        )->shouldReturn(true);

        $this->supports(
            Criteria::fromQueryParameters(
                'product',
                ['product_attribute' => 't-shirt-color', 'product_attribute_value' => 't-shirt-color-red']
            )
        )->shouldReturn(false);
    }
}
