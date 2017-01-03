<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Tests\Behat\Context\Domain\Shop;

use Behat\Behat\Context\Context;
use FOS\ElasticaBundle\Paginator\PaginatorAdapterInterface;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\SearchEngineInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\Product;
use Webmozart\Assert\Assert;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductContext implements Context
{
    /**
     * @var SearchEngineInterface
     */
    private $searchEngine;

    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @param SearchEngineInterface $searchEngine
     * @param SharedStorageInterface $sharedStorage
     */
    public function __construct(SearchEngineInterface $searchEngine, SharedStorageInterface $sharedStorage)
    {
        $this->searchEngine = $searchEngine;
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @When I filter them by :firstOptionValue color
     * @When I filter them by :firstOptionValue and :secondOptionValue color
     * @When I filter them by :firstOptionValue :secondOptionValue and :thirdOptionValue color
     */
    public function iFilterThemByBlueColor(... $optionValues)
    {
        $singleStringValue = '';
        foreach ($optionValues as $optionValue) {
            $singleStringValue .= sprintf('t_shirt_color_%s+', $optionValue);
        }

        $criteria = Criteria::fromQueryParameters(Product::class, ['product_option_code' => $singleStringValue]);
        $result = $this->searchEngine->match($criteria);

        $this->sharedStorage->set('search_result', $result);
    }

    /**
     * @Then I should see :numberOfProducts products in the list
     */
    public function iShouldSeeProductsInTheList($numberOfProducts)
    {
        /** @var PaginatorAdapterInterface $result */
        $result = $this->sharedStorage->get('search_result');

        Assert::eq($result->getTotalHits(), $numberOfProducts);
    }
}
