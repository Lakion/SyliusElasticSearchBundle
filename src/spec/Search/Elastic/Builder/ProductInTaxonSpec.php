<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Builder;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Builder\ProductInTaxon;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Specification\SpecificationInterface;
use ONGR\ElasticsearchDSL\Query\BoolQuery;
use ONGR\ElasticsearchDSL\Query\NestedQuery;
use ONGR\ElasticsearchDSL\Query\TermQuery;
use ONGR\ElasticsearchDSL\Search;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductInTaxonSpec extends ObjectBehavior
{
    function let(SpecificationInterface $productInMainTaxon, SpecificationInterface $productInProductTaxons)
    {
        $this->beConstructedWith($productInMainTaxon, $productInProductTaxons);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProductInTaxon::class);
    }

    function it_builds_search_query_for_given_specifications(
        SpecificationInterface $productInMainTaxon,
        SpecificationInterface $productInProductTaxons,
        NestedQuery $nestedQuery,
        TermQuery $termQuery,
        Search $search
    ) {
        $criteria = Criteria::fromQueryParameters('product', ['taxon_code' => 'mugs']);

        $productInMainTaxon->getQueryFor($criteria)->willReturn($termQuery);
        $productInProductTaxons->getQueryFor($criteria)->willReturn($nestedQuery);

        $search->addFilter($termQuery, BoolQuery::SHOULD)->shouldBeCalled();
        $search->addFilter($nestedQuery, BoolQuery::SHOULD)->shouldBeCalled();

        $this->build($criteria, $search);
    }

    function it_supports_criteria_if_they_satisfies_both_specifications(
        SpecificationInterface $productInMainTaxon,
        SpecificationInterface $productInProductTaxons
    ) {
        $criteria = Criteria::fromQueryParameters('product', []);
        $productInMainTaxon->satisfies($criteria)->willReturn(true);
        $productInProductTaxons->satisfies($criteria)->willReturn(true);

        $this->supports($criteria)->shouldReturn(true);
    }

    function it_does_not_support_criteria_if_they_do_not_satisfies_product_in_main_taxon_specification(
        SpecificationInterface $productInMainTaxon,
        SpecificationInterface $productInProductTaxons
    ) {
        $criteria = Criteria::fromQueryParameters('product', ['code' => 'banana']);
        $productInMainTaxon->satisfies($criteria)->willReturn(false);
        $productInProductTaxons->satisfies($criteria)->willReturn(true);

        $this->supports($criteria)->shouldReturn(false);
    }

    function it_does_not_support_criteria_if_they_do_not_satisfies_both_specifications(
        SpecificationInterface $productInMainTaxon,
        SpecificationInterface $productInProductTaxons
    ) {
        $criteria = Criteria::fromQueryParameters('product', ['code' => 'banana']);
        $productInMainTaxon->satisfies($criteria)->willReturn(false);
        $productInProductTaxons->satisfies($criteria)->willReturn(false);

        $this->supports($criteria)->shouldReturn(false);
    }

    function it_does_not_support_criteria_if_they_do_not_satisfies_product_in_product_taxons_specification(
        SpecificationInterface $productInMainTaxon,
        SpecificationInterface $productInProductTaxons
    ) {
        $criteria = Criteria::fromQueryParameters('product', ['code' => 'banana']);
        $productInMainTaxon->satisfies($criteria)->willReturn(true);
        $productInProductTaxons->satisfies($criteria)->willReturn(false);

        $this->supports($criteria)->shouldReturn(false);
    }
}
