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
use Sylius\Component\Core\Model\TaxonInterface;
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
     * @When I filter them by :optionValue :optionName in taxon :taxon
     */
    public function iFilterThemByBlueColor($optionValue, $optionName, TaxonInterface $taxon)
    {
        $criteria = Criteria::fromQueryParameters(Product::class, ['product_option_code' => sprintf('t_shirt_%s_%s', $optionName, $optionValue), 'product_option_value' => $optionValue, 'taxon_code' => $taxon->getCode()]);
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
