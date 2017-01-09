<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Query;

use Lakion\SyliusElasticSearchBundle\Exception\MissingQueryParameterException;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Query\ProductInMainTaxon;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Query\QueryInterface;
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

    function it_is_query()
    {
        $this->shouldImplement(QueryInterface::class);
    }

    function it_creates_query()
    {
        $this->setParameters(['taxon_code' => 'mugs']);

        $this->create()->shouldBeLike(new TermQuery('mainTaxon.code', 'mugs'));
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
