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
final class ProductInTaxon implements BuilderInterface
{
    /**
     * @var SpecificationInterface
     */
    private $productInMainTaxon;

    /**
     * @var SpecificationInterface
     */
    private $productInProductTaxons;

    /**
     * @param SpecificationInterface $productInMainTaxon
     * @param SpecificationInterface $productInProductTaxons
     */
    public function __construct(
        SpecificationInterface $productInMainTaxon,
        SpecificationInterface $productInProductTaxons
    ) {
        $this->productInMainTaxon = $productInMainTaxon;
        $this->productInProductTaxons = $productInProductTaxons;
    }

    /**
     * {@inheritdoc}
     */
    public function build(Criteria $criteria, Search $search)
    {
        $search->addFilter($this->productInMainTaxon->getQueryFor($criteria), BoolQuery::SHOULD);
        $search->addFilter($this->productInProductTaxons->getQueryFor($criteria), BoolQuery::SHOULD);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Criteria $criteria)
    {
        return $this->productInMainTaxon->satisfies($criteria) && $this->productInProductTaxons->satisfies($criteria);
    }
}
