<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Query;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Query\MatchProductByNameApplicator;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\SearchCriteriaApplicatorInterface;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query\QueryFactoryInterface;
use ONGR\ElasticsearchDSL\Query\MatchQuery;
use ONGR\ElasticsearchDSL\Search;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class MatchProductByNameApplicatorSpec extends ObjectBehavior
{
    function let(QueryFactoryInterface $matchProductNameQueryFactory)
    {
        $this->beConstructedWith($matchProductNameQueryFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(MatchProductByNameApplicator::class);
    }

    function it_is_criteria_search_applicator()
    {
        $this->shouldImplement(SearchCriteriaApplicatorInterface::class);
    }

    function it_applies_match_product_by_name_query(
        QueryFactoryInterface $matchProductNameQueryFactory,
        MatchQuery $matchQuery,
        Search $search
    ) {
        $criteria = Criteria::fromQueryParameters('product', ['search' => 'banana']);
        $matchProductNameQueryFactory->create(['search' => 'banana'])->willReturn($matchQuery);
        $search->addQuery($matchQuery)->shouldBeCalled();

        $this->apply($criteria, $search);
    }

    function it_supports_criteria_with_search_parameter()
    {
        $criteria = Criteria::fromQueryParameters('product', ['search' => 'banana']);

        $this->supports($criteria)->shouldReturn(true);
    }
}
