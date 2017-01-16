<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query;

use Lakion\SyliusElasticSearchBundle\Exception\MissingQueryParameterException;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query\ProductInProductTaxonsQueryFactory;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query\QueryFactoryInterface;
use ONGR\ElasticsearchDSL\Query\NestedQuery;
use ONGR\ElasticsearchDSL\Query\TermQuery;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductInProductTaxonsQueryFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ProductInProductTaxonsQueryFactory::class);
    }

    function it_is_query_factory()
    {
        $this->shouldImplement(QueryFactoryInterface::class);
    }

    function it_returns_query_for_given_criteria()
    {
        $this->create(['taxon_code' => 'mugs'])->shouldBeLike(new NestedQuery('productTaxons', new TermQuery('productTaxons.taxon.code', 'mugs')));
    }

    function it_cannot_create_query_if_there_is_no_required_parameters()
    {
        $this->shouldThrow(MissingQueryParameterException::class)->during('create', [['product_option_value' => 't-shirt-color']]);
    }

    function it_cannot_create_query_if_parameters_are_empty()
    {
        $this->shouldThrow(MissingQueryParameterException::class)->during('create', []);
    }
}
