<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Sort;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Ordering;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Sort\SortByFieldQueryFactory;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Sort\SortFactoryInterface;
use ONGR\ElasticsearchDSL\Sort\FieldSort;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class SortByFieldQueryFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SortByFieldQueryFactory::class);
    }

    function it_is_sort_factory()
    {
        $this->shouldImplement(SortFactoryInterface::class);
    }

    function it_creates_descending_field_sort_query()
    {
        $ordering = Ordering::fromQueryParameters(['sort' => '-price']);

        $this->create($ordering)->shouldBeLike(new FieldSort('raw_price', 'desc'));
    }

    function it_creates_ascending_field_sort_query()
    {
        $ordering = Ordering::fromQueryParameters(['sort' => 'price']);

        $this->create($ordering)->shouldBeLike(new FieldSort('raw_price', 'asc'));
    }

    function it_creates_ascending_by_name_field_sort_query_by_default()
    {
        $ordering = Ordering::fromQueryParameters([]);

        $this->create($ordering)->shouldBeLike(new FieldSort('raw_name', 'asc'));
    }
}
