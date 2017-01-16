<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Filter;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
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
        $criteria = Criteria::fromQueryParameters('product', ['channel_code' => 'web']);
        $productInChannelQueryFactory->create(['channel_code' => 'web'])->willReturn($nestedQuery);
        $search->addFilter($nestedQuery, BoolQuery::MUST)->shouldBeCalled();

        $this->apply($criteria, $search);
    }

    function it_supports_criteria_with_channel_code()
    {
        $criteria = Criteria::fromQueryParameters('product', ['channel_code' => 'web']);

        $this->supports($criteria)->shouldReturn(true);
    }

    function it_does_not_support_criteria_without_channel_code()
    {
        $criteria = Criteria::fromQueryParameters('product', []);

        $this->supports($criteria)->shouldReturn(false);
    }

    function it_does_not_support_criteria_without_channel_code_value()
    {
        $criteria = Criteria::fromQueryParameters('product', ['channel_code' => '']);

        $this->supports($criteria)->shouldReturn(false);

        $criteria = Criteria::fromQueryParameters('product', ['channel_code' => null]);

        $this->supports($criteria)->shouldReturn(false);
    }
}
