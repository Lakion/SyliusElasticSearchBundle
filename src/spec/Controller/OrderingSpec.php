<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Controller;

use Lakion\SyliusElasticSearchBundle\Controller\Ordering;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class OrderingSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Ordering::class);
    }

    function it_can_be_created_from_query_parameters_with_default_direction()
    {
        $this->beConstructedThrough('fromQueryParameters', [[
            'sort' => 'code',
        ]]);

        $this->getField()->shouldReturn('code');
        $this->getDirection()->shouldReturn('ASC');
    }

    function it_can_be_created_from_query_parameters()
    {
        $this->beConstructedThrough('fromQueryParameters', [[
            'sort' => '-code',
        ]]);

        $this->getField()->shouldReturn('code');
        $this->getDirection()->shouldReturn('DESC');
    }
}
