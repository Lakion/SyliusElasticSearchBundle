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
    private $emptyCriteria;

    /**
     * @param SpecificationInterface $emptyCriteria
     */
    public function __construct(SpecificationInterface $emptyCriteria)
    {
        $this->emptyCriteria = $emptyCriteria;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Criteria $criteria)
    {
        return $this->emptyCriteria->satisfies($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function build(Criteria $criteria, Search $search)
    {
        $search->addQuery($this->emptyCriteria->getQueryFor($criteria));
    }
}
