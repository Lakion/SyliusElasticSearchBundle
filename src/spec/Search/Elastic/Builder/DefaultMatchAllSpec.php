<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Builder;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Builder\BuilderInterface;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Builder\DefaultMatchAll;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Specification\SpecificationInterface;
use ONGR\ElasticsearchDSL\Query\MatchAllQuery;
use ONGR\ElasticsearchDSL\Search;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class DefaultMatchAllSpec extends ObjectBehavior
{
    function let(SpecificationInterface $specification)
    {
        $this->beConstructedWith($specification);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DefaultMatchAll::class);
    }

    function it_is_search_builder()
    {
        $this->shouldImplement(BuilderInterface::class);
    }

    function it_supports_criteria_if_they_satisfies_specification(SpecificationInterface $specification)
    {
        $criteria = Criteria::fromQueryParameters('product', []);
        $specification->satisfies($criteria)->willReturn(true);

        $this->supports($criteria)->shouldReturn(true);
    }

    function it_does_not_support_criteria_if_they_do_not_satisifies_speciciation(SpecificationInterface $specification)
    {
        $criteria = Criteria::fromQueryParameters('product', ['code' => 'banana']);
        $specification->satisfies($criteria)->willReturn(false);

        $this->supports($criteria)->shouldReturn(false);
    }

    function it_builds_search_query(SpecificationInterface $specification, Search $search, MatchAllQuery $matchAllQuery)
    {
        $criteria = Criteria::fromQueryParameters('product', []);

        $specification->getQueryFor($criteria)->willReturn($matchAllQuery);
        $search->addQuery($matchAllQuery)->shouldBeCalled();

        $this->build($criteria, $search);
    }
}
