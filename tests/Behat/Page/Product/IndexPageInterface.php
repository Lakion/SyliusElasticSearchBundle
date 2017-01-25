<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Lakion\SyliusElasticSearchBundle\Behat\Page\Product;

use Behat\Mink\Element\NodeElement;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Sylius\Behat\Page\SymfonyPageInterface;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
interface IndexPageInterface extends SymfonyPageInterface
{
    /**
     * @param Criteria $criteria
     */
    public function filterByProductOptions(Criteria $criteria);

    /**
     * @param int $graterThan
     * @param int $lessThan
     */
    public function filterByPriceRange($graterThan, $lessThan);

    public function filter();

    /**
     * @param int $perPage
     */
    public function setPaginating($perPage);

    /**
     * @param $phrase
     */
    public function search($phrase);

    /**
     * @return NodeElement[]
     */
    public function getAllProducts();
}
