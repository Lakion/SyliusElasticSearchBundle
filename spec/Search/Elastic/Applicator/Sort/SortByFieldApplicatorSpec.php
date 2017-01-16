<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Sort;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\SearchCriteriaApplicatorInterface;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Sort\SortByFieldApplicator;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Sort\SortFactoryInterface;
use ONGR\ElasticsearchDSL\Search;
use ONGR\ElasticsearchDSL\Sort\FieldSort;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class SortByFieldApplicatorSpec extends ObjectBehavior
{
    function let(SortFactoryInterface $sortByFieldSortFactory)
    {
        $this->beConstructedWith($sortByFieldSortFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SortByFieldApplicator::class);
    }

    function it_is_search_criteria_applicator()
    {
        $this->shouldImplement(SearchCriteriaApplicatorInterface::class);
    }

    function it_applies_sort_query_to_search_with_given_sorting(
        SortFactoryInterface $sortByFieldSortFactory,
        Search $search,
        FieldSort $fieldSort
    ) {
        $criteria = Criteria::fromQueryParameters('product', ['sort' => '-name']);
        $sortByFieldSortFactory->create($criteria->getOrdering())->willReturn($fieldSort);
        $search->addSort($fieldSort)->shouldBeCalled();

        $this->apply($criteria, $search);
    }

    function it_supports_criteria_with_sort_parameter()
    {
        $criteria = Criteria::fromQueryParameters('product', ['sort' => '-name']);

        $this->supports($criteria)->shouldReturn(true);
    }

    function it_support_sorting_by_default()
    {
        $criteria = Criteria::fromQueryParameters('product', []);

        $this->supports($criteria)->shouldReturn(true);
    }
}
