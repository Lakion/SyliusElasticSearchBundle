<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Filter;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Filter\ProductInTaxonApplicator;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\SearchCriteriaApplicatorInterface;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query\QueryFactoryInterface;
use ONGR\ElasticsearchDSL\Query\BoolQuery;
use ONGR\ElasticsearchDSL\Query\NestedQuery;
use ONGR\ElasticsearchDSL\Query\TermQuery;
use ONGR\ElasticsearchDSL\Search;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductInTaxonApplicatorSpec extends ObjectBehavior
{
    function let(QueryFactoryInterface $productInMainTaxon, QueryFactoryInterface $productInProductTaxons)
    {
        $this->beConstructedWith($productInMainTaxon, $productInProductTaxons);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProductInTaxonApplicator::class);
    }

    function it_is_search_criteria_applicator()
    {
        $this->shouldImplement(SearchCriteriaApplicatorInterface::class);
    }

    function it_applies_search_query_for_given_criteria(
        QueryFactoryInterface $productInMainTaxon,
        QueryFactoryInterface $productInProductTaxons,
        NestedQuery $nestedQuery,
        TermQuery $termQuery,
        Search $search
    ) {
        $criteria = Criteria::fromQueryParameters('product', ['taxon_code' => 'mugs']);

        $productInMainTaxon->create(['taxon_code' => 'mugs'])->willReturn($termQuery);
        $productInProductTaxons->create(['taxon_code' => 'mugs'])->willReturn($nestedQuery);

        $search->addFilter($termQuery, BoolQuery::SHOULD)->shouldBeCalled();
        $search->addFilter($nestedQuery, BoolQuery::SHOULD)->shouldBeCalled();

        $this->apply($criteria, $search);
    }

    function it_does_not_support_criteria_if_they_do_not_have_taxon_code()
    {
        $criteria = Criteria::fromQueryParameters('product', []);

        $this->supports($criteria)->shouldReturn(false);
    }

    function it_supports_criteria_if_they_have_taxon_code()
    {
        $criteria = Criteria::fromQueryParameters('product', ['taxon_code' => 'mugs']);

        $this->supports($criteria)->shouldReturn(true);
    }
}
