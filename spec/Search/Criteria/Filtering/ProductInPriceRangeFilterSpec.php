<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering\ProductInPriceRangeFilter;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductInPriceRangeFilterSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(100, 300);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProductInPriceRangeFilter::class);
    }

    function it_has_immutable_grater_than()
    {
        $this->getGraterThan()->shouldReturn(100);
    }

    function it_has_immutable_less_than()
    {
        $this->getLessThan()->shouldReturn(300);
    }
}
