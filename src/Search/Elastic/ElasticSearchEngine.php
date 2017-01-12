<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic;

use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use FOS\ElasticaBundle\Repository;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator\SearchCriteriaApplicatorInterface;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Search\SearchFactoryInterface;
use Lakion\SyliusElasticSearchBundle\Search\SearchEngineInterface;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ElasticSearchEngine implements SearchEngineInterface
{
    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;

    /**
     * @var SearchFactoryInterface
     */
    private $searchFactory;

    /**
     * @var SearchCriteriaApplicatorInterface[]
     */
    private $searchCriteriaApplicators = [];

    /**
     * @param RepositoryManagerInterface $repositoryManager
     * @param SearchFactoryInterface $searchFactory
     */
    public function __construct(RepositoryManagerInterface $repositoryManager, SearchFactoryInterface $searchFactory)
    {
        $this->repositoryManager = $repositoryManager;
        $this->searchFactory = $searchFactory;
    }

    /**
     * @param SearchCriteriaApplicatorInterface $searchCriteriaApplicator
     */
    public function addSearchCriteriaApplicator(SearchCriteriaApplicatorInterface $searchCriteriaApplicator)
    {
        $this->searchCriteriaApplicators[] = $searchCriteriaApplicator;
    }

    /**
     * {@inheritdoc}
     */
    public function match(Criteria $criteria)
    {
        $search = $this->searchFactory->create();
        foreach ($this->searchCriteriaApplicators as $applicator) {
            if ($applicator->supports($criteria)) {
                $applicator->apply($criteria, $search);
            }
        }

        /** @var Repository $repository */
        $repository = $this->repositoryManager->getRepository($criteria->getResourceAlias());

        return $repository->createPaginatorAdapter($search->toArray());
    }
}
