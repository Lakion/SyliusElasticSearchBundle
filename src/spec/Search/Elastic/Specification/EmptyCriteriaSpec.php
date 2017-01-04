<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Specification;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Specification\EmptyCriteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Specification\SpecificationInterface;
use ONGR\ElasticsearchDSL\Query\MatchAllQuery;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class EmptyCriteriaSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(EmptyCriteria::class);
    }

    function it_is_specification()
    {
        $this->shouldImplement(SpecificationInterface::class);
    }

    function it_does_not_have_parameter_key()
    {
        $this->getParameterKey()->shouldReturn(null);
    }

    function it_always_returns_match_all_query()
    {
        $this->getQueryFor(Criteria::fromQueryParameters('product', []))->shouldBeLike(new MatchAllQuery());
    }

    function it_satisfies_only_empty_criteria()
    {
        $this->satisfies(Criteria::fromQueryParameters('product', ['banana_property' => 'yellow']))->shouldReturn(false);
        $this->satisfies(Criteria::fromQueryParameters('product', []))->shouldReturn(true);
        $this->satisfies(Criteria::fromQueryParameters('taxon', ['some_awesome_property' => null, 'something_else' => '']))->shouldReturn(false);
    }
}
