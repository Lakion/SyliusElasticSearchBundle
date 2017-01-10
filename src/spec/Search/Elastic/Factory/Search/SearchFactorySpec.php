<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Search;

use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Search\SearchFactory;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Search\SearchFactoryInterface;
use ONGR\ElasticsearchDSL\Search;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class SearchFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SearchFactory::class);
    }

    function it_is_search_factory()
    {
        $this->shouldImplement(SearchFactoryInterface::class);
    }

    function it_creates_new_empty_search_object()
    {
        $this->create()->shouldHaveType(Search::class);
    }
}
