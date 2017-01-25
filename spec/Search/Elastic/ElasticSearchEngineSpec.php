<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Search\Elastic;

use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use FOS\ElasticaBundle\Paginator\PaginatorAdapterInterface;
use FOS\ElasticaBundle\Repository;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering\ProductHasOptionCodesFilter;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Ordering;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\SearchCriteriaApplicatorInterface;
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

    function it_returns_paginator_with_default_query_if_there_is_no_applicators_registered(
        RepositoryManagerInterface $repositoryManager,
        SearchFactoryInterface $searchFactory,
        Search $search,
        Repository $repository,
        SearchCriteriaApplicatorInterface $orderingApplicator,
        PaginatorAdapterInterface $paginatorAdapter
    ) {
        $criteria = Criteria::fromQueryParameters('product', []);
        $searchFactory->create()->willReturn($search);
        $repositoryManager->getRepository('product')->willReturn($repository);

        $this->addSearchCriteriaApplicator($orderingApplicator, Ordering::class);
        $orderingApplicator->apply($criteria->getOrdering(), $search)->shouldBeCalled();

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

    function it_returns_resources_based_on_applicators_which_supports_given_criteria(
        RepositoryManagerInterface $repositoryManager,
        SearchFactoryInterface $searchFactory,
        Search $search,
        Repository $repository,
        PaginatorAdapterInterface $paginatorAdapter,
        SearchCriteriaApplicatorInterface $productHasOptionCodesApplicator,
        SearchCriteriaApplicatorInterface $orderingApplicator
    ) {
        $productHasOptionCodesFilter = new ProductHasOptionCodesFilter(['mug_type']);
        $criteria = Criteria::fromQueryParameters('product', [$productHasOptionCodesFilter]);
        $this->addSearchCriteriaApplicator($productHasOptionCodesApplicator, ProductHasOptionCodesFilter::class);
        $this->addSearchCriteriaApplicator($orderingApplicator, Ordering::class);
        $searchFactory->create()->willReturn($search);

        $productHasOptionCodesApplicator->apply($productHasOptionCodesFilter, $search)->shouldBeCalled();
        $orderingApplicator->apply($criteria->getOrdering(), $search)->shouldBeCalled();

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

    function it_does_not_apply_none_object_filter(
        RepositoryManagerInterface $repositoryManager,
        SearchFactoryInterface $searchFactory,
        Search $search,
        Repository $repository,
        PaginatorAdapterInterface $paginatorAdapter,
        SearchCriteriaApplicatorInterface $productHasOptionCodesApplicator,
        SearchCriteriaApplicatorInterface $orderingApplicator
    ) {
        $criteria = Criteria::fromQueryParameters('product', [null]);
        $this->addSearchCriteriaApplicator($productHasOptionCodesApplicator, ProductHasOptionCodesFilter::class);
        $this->addSearchCriteriaApplicator($orderingApplicator, Ordering::class);
        $searchFactory->create()->willReturn($search);

        $productHasOptionCodesApplicator->apply(null, $search)->shouldNotBeCalled();
        $orderingApplicator->apply($criteria->getOrdering(), $search)->shouldBeCalled();

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
