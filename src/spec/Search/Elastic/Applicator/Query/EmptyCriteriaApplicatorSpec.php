<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Query;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Query\EmptyCriteriaApplicator;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\SearchCriteriaApplicatorInterface;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query\QueryFactoryInterface;
use ONGR\ElasticsearchDSL\Query\MatchAllQuery;
use ONGR\ElasticsearchDSL\Search;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class EmptyCriteriaApplicatorSpec extends ObjectBehavior
{
    function let(QueryFactoryInterface $matchAllQueryFactory)
    {
        $this->beConstructedWith($matchAllQueryFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EmptyCriteriaApplicator::class);
    }

    function it_is_search_criteria_applicator()
    {
        $this->shouldImplement(SearchCriteriaApplicatorInterface::class);
    }

    function it_supports_criteria_if_they_satisfies_specification()
    {
        $criteria = Criteria::fromQueryParameters('product', []);

        $this->supports($criteria)->shouldReturn(true);
    }

    function it_does_not_support_criteria_if_they_do_not_satisifies_speciciation()
    {
        $criteria = Criteria::fromQueryParameters('product', ['query' => 'banana']);

        $this->supports($criteria)->shouldReturn(false);
    }

    function it_builds_search_query(QueryFactoryInterface $matchAllQueryFactory, Search $search, MatchAllQuery $matchAllQuery)
    {
        $criteria = Criteria::fromQueryParameters('product', []);

        $matchAllQueryFactory->create()->willReturn($matchAllQuery);
        $search->addQuery($matchAllQuery)->shouldBeCalled();

        $this->apply($criteria, $search);
    }
}
