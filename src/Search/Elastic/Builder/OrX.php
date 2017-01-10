<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Builder;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Specification\SpecificationInterface;
use ONGR\ElasticsearchDSL\Query\BoolQuery;
use ONGR\ElasticsearchDSL\Search;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class OrX implements BuilderInterface
{
    /**
     * @var SpecificationInterface
     */
    private $specification;

    /**
     * @param SpecificationInterface $specification
     */
    public function __construct(SpecificationInterface $specification)
    {
        $this->specification = $specification;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(Criteria $criteria)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function build(Criteria $criteria, Search $search)
    {
        if (!isset($criteria->getFiltering()->getFields()['filter_set'])) {
            return;
        }

        $filterSet = $criteria->getFiltering()->getFields()['filter_set'];
        foreach ($filterSet as $filterSetName => $filterSetValue) {
            foreach ($filterSetValue as $filterName => $filterValue) {
                if ($this->specification->getParameterKey() === $filterName) {
                    foreach ($filterValue as $value) {
                        $search->addFilter(
                            $this->specification->getQueryFor(Criteria::fromQueryParameters($criteria->getResourceAlias(), [$this->specification->getParameterKey() => $value])),
                            BoolQuery::SHOULD
                        );
                    }
                }
            }
        }
    }
}
