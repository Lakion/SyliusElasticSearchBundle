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
    const DELIMITER = '+';

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
        return $this->specification->satisfies($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function build(Criteria $criteria, Search $search)
    {
        $filteringValues = explode(self::DELIMITER, $criteria->getFiltering()->getFields()[$this->specification->getParameterKey()]);

        foreach ($filteringValues as $value) {
            $search->addFilter(
                $this->specification->getQueryFor(Criteria::fromQueryParameters($criteria->getResourceAlias(), [$this->specification->getParameterKey() => $value])),
                BoolQuery::SHOULD
            );
        }
    }
}
