<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic;

use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use FOS\ElasticaBundle\Paginator\PaginatorAdapterInterface;
use FOS\ElasticaBundle\Repository;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Builder\BuilderInterface;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\ElasticSearchEngine;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Search\SearchFactoryInterface;
use Lakion\SyliusElasticSearchBundle\Search\SearchEngineInterface;
use ONGR\ElasticsearchDSL\Search;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ElasticSearchEngineSpec extends ObjectBehavior
{
    function let(RepositoryManagerInterface $repositoryManager, SearchFactoryInterface $searchFactory)
    {
        $this->beConstructedWith($repositoryManager, $searchFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ElasticSearchEngine::class);
    }

    function it_is_search_engine()
    {
        $this->shouldImplement(SearchEngineInterface::class);
    }

    function it_returns_paginator_with_default_query_if_there_is_no_builders_registered(
        RepositoryManagerInterface $repositoryManager,
        SearchFactoryInterface $searchFactory,
        Search $search,
        Repository $repository,
        PaginatorAdapterInterface $paginatorAdapter
    ) {
        $searchFactory->create()->willReturn($search);
        $repositoryManager->getRepository('product')->willReturn($repository);
        $search->toArray()->willReturn([
            'query' => [
                'match_all' => new \stdClass(),
            ]
        ]);

        $repository->createPaginatorAdapter([
            'query' => [
                'match_all' => new \stdClass(),
            ]
        ])->willReturn($paginatorAdapter);

        $this->match(Criteria::fromQueryParameters('product', ['name' => 'banana']))->shouldReturn($paginatorAdapter);
    }

    function it_returns_resources_based_on_builders_which_supports_given_criteria(
        RepositoryManagerInterface $repositoryManager,
        SearchFactoryInterface $searchFactory,
        Search $search,
        Repository $repository,
        PaginatorAdapterInterface $paginatorAdapter,
        BuilderInterface $productByOptionBuilder
    ) {
        $criteria = Criteria::fromQueryParameters('product', ['name' => 'banana']);
        $this->addBuilder($productByOptionBuilder);
        $searchFactory->create()->willReturn($search);

        $productByOptionBuilder->supports($criteria)->willReturn(true);
        $productByOptionBuilder->build($criteria, $search)->shouldBeCalled();

        $repositoryManager->getRepository('product')->willReturn($repository);
        $search->toArray()->willReturn([
            'query' => [
                'match_all' => new \stdClass(),
            ]
        ]);

        $repository->createPaginatorAdapter([
            'query' => [
                'match_all' => new \stdClass(),
            ]
        ])->willReturn($paginatorAdapter);

        $this->match($criteria)->shouldReturn($paginatorAdapter);
    }
}
