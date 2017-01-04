<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Builder;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Specification\SpecificationInterface;
use ONGR\ElasticsearchDSL\Search;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class DefaultMatchAll implements BuilderInterface
{
    /**
     * @var SpecificationInterface
     */
    private $defaultMatchAll;

    /**
     * @param SpecificationInterface $defaultMatchAll
     */
    public function __construct(SpecificationInterface $defaultMatchAll)
    {
        $this->defaultMatchAll = $defaultMatchAll;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Criteria $criteria)
    {
        return $this->defaultMatchAll->satisfies($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function build(Criteria $criteria, Search $search)
    {
        $search->addQuery($this->defaultMatchAll->getQueryFor($criteria));
    }
}
