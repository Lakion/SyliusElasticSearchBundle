<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Lakion\SyliusElasticSearchBundle\Behat\Context\Domain\Shop;

use Behat\Behat\Context\Context;
use FOS\ElasticaBundle\Paginator\PaginatorAdapterInterface;
use FOS\ElasticaBundle\Paginator\PartialResultsInterface;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering\ProductHasOptionCodesFilter;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering\ProductInChannelFilter;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering\ProductInPriceRangeFilter;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering\ProductInTaxonFilter;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\SearchPhrase;
use Lakion\SyliusElasticSearchBundle\Search\SearchEngineInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductInterface;
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
     * @When I filter them by :mugTypeValue mug type
     */
    public function iFilterThemByDoubleMugType($mugTypeValue)
    {
        $criteria = Criteria::fromQueryParameters(Product::class, [
            new ProductHasOptionCodesFilter([sprintf('mug_type_%s', $mugTypeValue)]),
        ]);

        $this->match($criteria);
    }

    /**
     * @When I filter them by :mugTypeValue mug type or sticker size :stickerSizeValue
     */
    public function iFilterThemByDoubleMugTypeAndStickerSize($mugTypeValue, $stickerSizeValue)
    {
        $criteria = Criteria::fromQueryParameters(Product::class, [
            new ProductHasOptionCodesFilter([sprintf('mug_type_%s', $mugTypeValue)]),
            new ProductHasOptionCodesFilter([sprintf('sticker_size_%s', $stickerSizeValue)]),
        ]);

        $this->match($criteria);
    }

    /**
     * @When I filter them by stickier size :stickerSizeValue
     */
    public function iFilterThemByStickierSize($stickerSizeValue)
    {
        $criteria = Criteria::fromQueryParameters(Product::class, [
            new ProductHasOptionCodesFilter([sprintf('sticker_size_%s', $stickerSizeValue)]),
        ]);

        $this->match($criteria);
    }

    /**
     * @When /^I filter them by price between ("[^"]+") and ("[^"]+")$/
     */
    public function iFilterThemByPriceBetweenAnd($graterThan, $lessThan)
    {
        sleep(3);
        $criteria = Criteria::fromQueryParameters(Product::class, [new ProductInPriceRangeFilter($graterThan, $lessThan)]);
        $this->match($criteria);
    }

    /**
     * @When I view the list of the products without filtering
     */
    public function iViewTheListOfTheProductsWithoutFiltering()
    {
        $criteria = Criteria::fromQueryParameters(Product::class, []);
        $this->match($criteria);
    }

    /**
     * @When /^I filter them by (channel "[^"]+")$/
     */
    public function iFilterThemByChannel(ChannelInterface $channel)
    {
        sleep(3);
        $criteria = Criteria::fromQueryParameters(Product::class, [new ProductInChannelFilter($channel->getCode())]);
        $this->match($criteria);
    }

    /**
     * @When /^I filter them by (channel "[^"]+") and price between ("[^"]+") and ("[^"]+")$/
     */
    public function iFilterThemByChannelAndPriceBetweenAnd(ChannelInterface $channel, $graterThan, $lessThan)
    {
        sleep(3);
        $criteria = Criteria::fromQueryParameters(Product::class, [
            new ProductInChannelFilter($channel->getCode()),
            new ProductInPriceRangeFilter($graterThan, $lessThan),
        ]);

        $this->match($criteria);
    }

    /**
     * @When I filter them by :taxon taxon
     */
    public function iFilterThemByTaxon(TaxonInterface $taxon)
    {
        $criteria = Criteria::fromQueryParameters(Product::class, [new ProductInTaxonFilter($taxon->getCode())]);
        $this->match($criteria);
    }

    /**
     * @When I sort them by :field in :order order
     */
    public function iSortThemByNameInAscendingOrder($field, $order)
    {
        sleep(3);
        if ('descending' === $order) {
            $field = '-' . $field;
        }

        $criteria = Criteria::fromQueryParameters(Product::class, ['sort' => $field]);
        $this->match($criteria);
    }

    /**
     * @When I search for products with name :name
     */
    public function iSearchForProductsWithName($name)
    {
        sleep(3);

        $criteria = Criteria::fromQueryParameters(Product::class, [new SearchPhrase($name)]);
        $this->match($criteria);
    }

    /**
     * @Then I should see :numberOfProducts products on the list
     */
    public function iShouldSeeProductsOnTheList($numberOfProducts)
    {
        /** @var PaginatorAdapterInterface $result */
        $result = $this->sharedStorage->get('search_result');

        Assert::eq($result->getTotalHits(), $numberOfProducts);
    }

    /**
     * @Then /^I should see products in order like "([^"]+)", "([^"]+)", "([^"]+)", "([^"]+)", "([^"]+)", "([^"]+)"$/
     */
    public function iShouldSeeProductsInOrderLike(...$productNames)
    {
        /** @var PaginatorAdapterInterface $result */
        $searchResult = $this->sharedStorage->get('search_result');

        /** @var PartialResultsInterface $partialResult */
        $partialResult = $searchResult->getResults(0, 100);

        /**
         * @var int $position
         * @var ProductInterface $result
         */
        foreach ($partialResult->toArray() as $position => $result) {
            if ($result->getName() !== $productNames[$position]) {
                throw new \RuntimeException(
                    sprintf(
                        'Sorting failed at position "%s" expected value was "%s", but got "%s"',
                        $position + 1,
                        $productNames[$position],
                        $result
                    )
                );
            }
        }
    }

    /**
     * @Then /^It should be "([^"]+)"$/
     * @Then /^It should be "([^"]+)", "([^"]+)"$/
     * @Then /^It should be "([^"]+)", "([^"]+)", "([^"]+)"$/
     * @Then /^It should be "([^"]+)", "([^"]+)", "([^"]+)", "([^"]+)"$/
     */
    public function itShouldBe(...$expectedProductNames)
    {
        /** @var PaginatorAdapterInterface $result */
        $searchResult = $this->sharedStorage->get('search_result');

        /** @var PartialResultsInterface $partialResult */
        $partialResult = $searchResult->getResults(0, 100);

        $resultProductNames = array_map(function (ProductInterface $product) {
            return $product->getName();
        }, $partialResult->toArray());

        $expectedProductCount = count($expectedProductNames);
        $resultProductCount = count($resultProductNames);

        Assert::same($expectedProductCount, $resultProductCount);

        foreach ($expectedProductNames as $expectedProductName) {
            Assert::oneOf(
                $expectedProductName,
                $resultProductNames,
                sprintf(
                    'Expected product with name "%s", does not exist in search result. Got "%s"',
                    $expectedProductName,
                    implode(',', $resultProductNames)
                )
            );
        }
    }

    /**
     * @param Criteria $criteria
     */
    private function match(Criteria $criteria)
    {
        $result = $this->searchEngine->match($criteria);

        $this->sharedStorage->set('search_result', $result);
    }
}
