<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Criteria;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Paginating;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class PaginatingSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Paginating::class);
    }

    function it_can_be_created_form_query_parameters_with_default_values_if_parameters_are_empty()
    {
        $this->beConstructedThrough('fromQueryParameters', [[]]);

        $this->getCurrentPage()->shouldReturn(1);
        $this->getItemsPerPage()->shouldReturn(10);
        $this->getOffset()->shouldReturn(0);
    }

    function it_can_be_created_from_query_parameters_with_default_values_if_parameters_are_not_valid()
    {
        $this->beConstructedThrough('fromQueryParameters', [[
            'page' => -1,
            'per_page' => -100,
        ]]);

        $this->getCurrentPage()->shouldReturn(1);
        $this->getItemsPerPage()->shouldReturn(10);
        $this->getOffset()->shouldReturn(0);
    }

    function it_can_be_created_from_query_parameters()
    {
        $this->beConstructedThrough('fromQueryParameters', [[
            'page' => 2,
            'per_page' => 50,
        ]]);

        $this->getCurrentPage()->shouldReturn(2);
        $this->getItemsPerPage()->shouldReturn(50);
        $this->getOffset()->shouldReturn(50);
    }
}
