<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Builder;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Builder\BuilderInterface;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Builder\OrX;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Specification\SpecificationInterface;
use ONGR\ElasticsearchDSL\Query\BoolQuery;
use ONGR\ElasticsearchDSL\Query\TermQuery;
use ONGR\ElasticsearchDSL\Search;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class OrXSpec extends ObjectBehavior
{
    function let(SpecificationInterface $specification)
    {
        $this->beConstructedWith($specification);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(OrX::class);
    }

    function it_is_search_builder()
    {
        $this->shouldImplement(BuilderInterface::class);
    }

    function it_builds_disjunction_of_several_specifications(
        SpecificationInterface $specification,
        Search $search,
        TermQuery $termQuery
    ) {
        $criteria = Criteria::fromQueryParameters('product', ['name' => 'ben+peter+mike']);

        $specification->getParameterKey()->willReturn('name');
        $specification->getQueryFor(Criteria::fromQueryParameters('product', ['name' => 'ben']))->willReturn($termQuery);
        $specification->getQueryFor(Criteria::fromQueryParameters('product', ['name' => 'peter']))->willReturn($termQuery);
        $specification->getQueryFor(Criteria::fromQueryParameters('product', ['name' => 'mike']))->willReturn($termQuery);
        $search->addFilter($termQuery, BoolQuery::SHOULD)->shouldBeCalledTimes(3);

        $this->build($criteria, $search);
    }

    function it_builds_query_for_single_parameter(
        SpecificationInterface $specification,
        Search $search,
        TermQuery $termQuery
    ) {
        $criteria = Criteria::fromQueryParameters('product', ['name' => 'ben']);
        $specification->getParameterKey()->willReturn('name');
        $specification->getQueryFor($criteria)->willReturn($termQuery);
        $search->addFilter($termQuery, BoolQuery::SHOULD)->shouldBeCalled();

        $this->build($criteria, $search);
    }

    function it_supports_criteria_if_satisfies_specification(SpecificationInterface $specification)
    {
        $criteria = Criteria::fromQueryParameters('product', ['name' => 'ben']);
        $specification->satisfies($criteria)->willReturn(true);

        $this->supports($criteria)->shouldReturn(true);
    }

    function it_does_not_support_criteria_if_does_not_satisfies_specification(SpecificationInterface $specification)
    {
        $criteria = Criteria::fromQueryParameters('product', ['name' => 'ben']);
        $specification->satisfies($criteria)->willReturn(false);

        $this->supports($criteria)->shouldReturn(false);
    }
}
