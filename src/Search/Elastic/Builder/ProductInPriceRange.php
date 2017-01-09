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
use ONGR\ElasticsearchDSL\Search;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductInPriceRange implements BuilderInterface
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
     * {@inheritdoc}
     */
    public function supports(Criteria $criteria)
    {
        if (!isset($criteria->getFiltering()->getFields()['product_price_range'])) {
            return false;
        }

        $filtering = $criteria->getFiltering()->getFields()['product_price_range'];

        return array_key_exists('grater_than', $filtering) && array_key_exists('less_than', $filtering);
    }

    /**
     * {@inheritdoc}
     */
    public function build(Criteria $criteria, Search $search)
    {
        $search->addFilter($this->specification->getQueryFor($criteria));
    }
}
