<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Filter;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\Filter\ProductHasMultipleOptionCodesApplicator;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\SearchCriteriaApplicatorInterface;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query\QueryFactoryInterface;
use ONGR\ElasticsearchDSL\Query\BoolQuery;
use ONGR\ElasticsearchDSL\Query\TermQuery;
use ONGR\ElasticsearchDSL\Search;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductHasMultipleOptionCodesApplicatorSpec extends ObjectBehavior
{
    function let(QueryFactoryInterface $productHasOptionCodeQueryFactory)
    {
        $this->beConstructedWith($productHasOptionCodeQueryFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProductHasMultipleOptionCodesApplicator::class);
    }

    function it_is_criteria_search_applicator()
    {
        $this->shouldImplement(SearchCriteriaApplicatorInterface::class);
    }

    function it_applies_search_query_for_multiple_product_options(
        QueryFactoryInterface $productHasOptionCodeQueryFactory,
        TermQuery $mediumMugTermQuery,
        TermQuery $stickerSizeTermQuery,
        Search $search
    ) {
        $criteria = Criteria::fromQueryParameters('product', ['product_option_code' => 'medium_mug+sticker_size_1']);
        $productHasOptionCodeQueryFactory->create(['option_value_code' => 'medium_mug'])->willReturn($mediumMugTermQuery);
        $productHasOptionCodeQueryFactory->create(['option_value_code' => 'sticker_size_1'])->willReturn($stickerSizeTermQuery);

        $search->addFilter($mediumMugTermQuery, BoolQuery::SHOULD)->shouldBeCalled();
        $search->addFilter($stickerSizeTermQuery, BoolQuery::SHOULD)->shouldBeCalled();

        $this->apply($criteria, $search);
    }

    function it_applies_search_query_for_single_product_option(
        QueryFactoryInterface $productHasOptionCodeQueryFactory,
        TermQuery $mediumMugTermQuery,
        Search $search
    ) {
        $criteria = Criteria::fromQueryParameters('product', ['product_option_code' => 'medium_mug']);
        $productHasOptionCodeQueryFactory->create(['option_value_code' => 'medium_mug'])->willReturn($mediumMugTermQuery);

        $search->addFilter($mediumMugTermQuery, BoolQuery::SHOULD)->shouldBeCalled();

        $this->apply($criteria, $search);
    }

    function it_supports_given_criteria()
    {
        $criteria = Criteria::fromQueryParameters('product', ['product_option_code' => 'medium_mug']);

        $this->supports($criteria)->shouldReturn(true);
    }

    function it_does_not_support_criteria_without_product_option_code_parameter()
    {
        $criteria = Criteria::fromQueryParameters('product', ['product_option_value' => 'Medium']);

        $this->supports($criteria)->shouldReturn(false);
    }

    function it_does_not_support_criteria_without_filtering_parameters()
    {
        $criteria = Criteria::fromQueryParameters('product', []);

        $this->supports($criteria)->shouldReturn(false);
    }
}
