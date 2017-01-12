<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Criteria;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Ordering;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Paginating;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class CriteriaSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Criteria::class);
    }

    function it_is_created_from_query_parameters_and_resource_alias()
    {
        $this->beConstructedThrough('fromQueryParameters', ['sylius.product', [
            'page' => 2,
            'per_page' => 50,
            'sort' => '-price',
            'option' => 'red',
        ]]);

        $this->getResourceAlias()->shouldReturn('sylius.product');
        $this->getFiltering()->shouldBeLike(Filtering::fromQueryParameters([
            'page' => 2,
            'per_page' => 50,
            'sort' => '-price',
            'option' => 'red',
        ]));
        $this->getPaginating()->shouldBeLike(Paginating::fromQueryParameters([
            'page' => 2,
            'per_page' => 50,
            'sort' => '-price',
            'option' => 'red',
        ]));
        $this->getOrdering()->shouldBeLike(Ordering::fromQueryParameters([
            'page' => 2,
            'per_page' => 50,
            'sort' => '-price',
            'option' => 'red',
        ]));
    }
}
