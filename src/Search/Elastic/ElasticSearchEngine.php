<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic;

use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use FOS\ElasticaBundle\Repository;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Builder\BuilderInterface;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\SearchFactoryInterface;
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
     * @var BuilderInterface[]
     */
    private $builders = [];

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
     * @param BuilderInterface $builder
     */
    public function addBuilder(BuilderInterface $builder)
    {
        $this->builders[] = $builder;
    }

    /**
     * {@inheritdoc}
     */
    public function match(Criteria $criteria)
    {
        $search = $this->searchFactory->create();
        foreach ($this->builders as $builder) {
            if ($builder->supports($criteria)) {
                $builder->build($criteria, $search);
            }
        }

        /** @var Repository $repository */
        $repository = $this->repositoryManager->getRepository($criteria->getResourceAlias());

        return $repository->createPaginatorAdapter($search->toArray());
    }
}
