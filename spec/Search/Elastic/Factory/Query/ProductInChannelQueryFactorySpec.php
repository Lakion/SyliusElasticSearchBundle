<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query;

use Lakion\SyliusElasticSearchBundle\Exception\MissingQueryParameterException;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query\ProductInChannelQueryFactory;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query\QueryFactoryInterface;
use ONGR\ElasticsearchDSL\Query\NestedQuery;
use ONGR\ElasticsearchDSL\Query\TermQuery;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductInChannelQueryFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ProductInChannelQueryFactory::class);
    }

    function it_is_query_factory()
    {
        $this->shouldImplement(QueryFactoryInterface::class);
    }

    function it_creates_product_in_channel_query()
    {
        $this->create(['channel_code' => 'web'])->shouldBeLike(
            new NestedQuery('channels', new TermQuery('channels.code', 'web'))
        );
    }

    function it_cannot_be_created_without_channel_code()
    {
        $this->shouldThrow(MissingQueryParameterException::class)->during('create', []);
    }
}
