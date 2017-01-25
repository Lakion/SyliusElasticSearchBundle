<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering\ProductInTaxonFilter;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductInTaxonFilterSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('mugs');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProductInTaxonFilter::class);
    }

    function it_has_immutable_taxon_code()
    {
        $this->getTaxonCode()->shouldReturn('mugs');
    }
}
