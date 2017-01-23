<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Filter;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering\ProductInChannelFilter;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Filter\ProductInChannelApplicator;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\SearchCriteriaApplicatorInterface;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query\QueryFactoryInterface;
use ONGR\ElasticsearchDSL\Query\BoolQuery;
use ONGR\ElasticsearchDSL\Query\NestedQuery;
use ONGR\ElasticsearchDSL\Search;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductInChannelApplicatorSpec extends ObjectBehavior
{
    function let(QueryFactoryInterface $productInChannelQueryFactory)
    {
        $this->beConstructedWith($productInChannelQueryFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProductInChannelApplicator::class);
    }

    function it_is_search_criteria_applicator()
    {
        $this->shouldImplement(SearchCriteriaApplicatorInterface::class);
    }

    function it_applies_search_criteria_with_channel_code(
        QueryFactoryInterface $productInChannelQueryFactory,
        Search $search,
        NestedQuery $nestedQuery
    ) {
        $criteria = new ProductInChannelFilter('web');
        $productInChannelQueryFactory->create(['channel_code' => 'web'])->willReturn($nestedQuery);
        $search->addFilter($nestedQuery, BoolQuery::MUST)->shouldBeCalled();

        $this->apply($criteria, $search);
    }
}
