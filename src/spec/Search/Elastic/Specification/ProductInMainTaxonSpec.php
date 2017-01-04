<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Specification;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Specification\ProductInMainTaxon;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Specification\SpecificationInterface;
use ONGR\ElasticsearchDSL\Query\TermQuery;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductInMainTaxonSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ProductInMainTaxon::class);
    }

    function it_is_specification()
    {
        $this->shouldImplement(SpecificationInterface::class);
    }

    function it_satisfies_criteria()
    {
        $criteria = Criteria::fromQueryParameters('product', ['taxon_code' => 'mugs']);

        $this->satisfies($criteria)->shouldReturn(true);
    }

    function it_does_not_satisfies_criteria_without_taxon_code()
    {
        $criteria = Criteria::fromQueryParameters('product', ['color' => 'banana']);

        $this->satisfies($criteria)->shouldReturn(false);
    }

    function it_does_not_satisifies_empty_criteria()
    {
        $criteria = Criteria::fromQueryParameters('product', []);

        $this->satisfies($criteria)->shouldReturn(false);
    }

    function it_returns_query_for_given_criteria()
    {
        $criteria = Criteria::fromQueryParameters('product', ['taxon_code' => 'mugs']);

        $this->getQueryFor($criteria)->shouldBeLike(new TermQuery('mainTaxon.code', 'mugs'));
    }

    function it_returns_query_for_given_criteria_and_it_is_not_case_sensitive()
    {
        $criteria = Criteria::fromQueryParameters('product', ['taxon_code' => 'Mugs']);

        $this->getQueryFor($criteria)->shouldBeLike(new TermQuery('mainTaxon.code', 'mugs'));
    }

    function it_has_parameter_key()
    {
        $this->getParameterKey()->shouldReturn('taxon_code');
    }
}
