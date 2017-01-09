<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Query;

use Lakion\SyliusElasticSearchBundle\Search\Elastic\Query\EmptyCriteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Query\QueryInterface;
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

    function it_is_query()
    {
        $this->shouldImplement(QueryInterface::class);
    }

    function it_does_not_have_parameters()
    {
        $this->shouldThrow(\RuntimeException::class)->during('setParameters', [[]]);
    }

    function it_creates_query()
    {
        $this->create()->shouldBeLike(new MatchAllQuery());
    }
}
