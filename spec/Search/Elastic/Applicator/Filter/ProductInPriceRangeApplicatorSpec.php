<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Filter;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Filter\ProductInPriceRangeApplicator;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\SearchCriteriaApplicatorInterface;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query\QueryFactoryInterface;
use ONGR\ElasticsearchDSL\Query\BoolQuery;
use ONGR\ElasticsearchDSL\Query\NestedQuery;
use ONGR\ElasticsearchDSL\Search;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductInPriceRangeApplicatorSpec extends ObjectBehavior
{
    function let(QueryFactoryInterface $productInPriceRangeQueryFactory)
    {
        $this->beConstructedWith($productInPriceRangeQueryFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProductInPriceRangeApplicator::class);
    }

    function it_is_search_criteria_applicator()
    {
        $this->shouldImplement(SearchCriteriaApplicatorInterface::class);
    }

    function it_applies_search_criteria_for_given_query(QueryFactoryInterface $productInPriceRangeQueryFactory, Search $search, NestedQuery $nestedQuery)
    {
        $criteria = Criteria::fromQueryParameters('product', ['product_price_range' => ['grater_than' => 20, 'less_than' => 50]]);

        $productInPriceRangeQueryFactory->create(['product_price_range' => ['grater_than' => 20, 'less_than' => 50]])->willReturn($nestedQuery);
        $search->addFilter($nestedQuery, BoolQuery::MUST)->shouldBeCalled();

        $this->apply($criteria, $search);
    }

    function it_supports_criteria_when_it_has_all_query_parameters()
    {
        $criteria = Criteria::fromQueryParameters('product', ['product_price_range' => ['grater_than' => 20, 'less_than' => 50]]);

        $this->supports($criteria)->shouldReturn(true);
    }

    function it_does_not_support_criteria_when_product_price_range_parameter_is_missing()
    {
        $criteria = Criteria::fromQueryParameters('product', ['taxon_code' => 'mugs']);

        $this->supports($criteria)->shouldReturn(false);
    }

    function it_does_not_support_criteria_when_grater_than_paramter_is_missing()
    {
        $criteria = Criteria::fromQueryParameters('product', ['product_price_range' => ['less_than' => 50]]);

        $this->supports($criteria)->shouldReturn(false);
    }

    function it_does_not_support_criteria_when_less_than_paramter_is_missing()
    {
        $criteria = Criteria::fromQueryParameters('product', ['product_price_range' => ['grater_than' => 50]]);

        $this->supports($criteria)->shouldReturn(false);
    }
}
