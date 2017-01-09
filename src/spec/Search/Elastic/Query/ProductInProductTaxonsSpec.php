<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Query;

use Lakion\SyliusElasticSearchBundle\Exception\MissingQueryParameterException;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Query\ProductInProductTaxons;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Query\QueryInterface;
use ONGR\ElasticsearchDSL\Query\NestedQuery;
use ONGR\ElasticsearchDSL\Query\TermQuery;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductInProductTaxonsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ProductInProductTaxons::class);
    }

    function it_is_query()
    {
        $this->shouldImplement(QueryInterface::class);
    }

    function it_returns_query_for_given_criteria()
    {
        $this->setParameters(['taxon_code' => 'mugs']);

        $this->create()->shouldBeLike(new NestedQuery('productTaxons', new TermQuery('productTaxons.taxon.code', 'mugs')));
    }

    function it_cannot_create_query_if_there_is_no_required_parameters()
    {
        $this->setParameters(['product_option_value' => 't-shirt-color']);

        $this->shouldThrow(MissingQueryParameterException::class)->during('create', []);
    }

    function it_cannot_create_query_if_parameters_are_empty()
    {
        $this->shouldThrow(MissingQueryParameterException::class)->during('create', []);
    }
}
