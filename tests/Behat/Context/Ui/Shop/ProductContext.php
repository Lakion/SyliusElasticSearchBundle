<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Lakion\SyliusElasticSearchBundle\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Behat\Mink\Element\NodeElement;
use FOS\ElasticaBundle\Paginator\PaginatorAdapterInterface;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Tests\Lakion\SyliusElasticSearchBundle\Behat\Page\Product\IndexPageInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\Product;
use Webmozart\Assert\Assert;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductContext implements Context
{
    /**
     * @var IndexPageInterface
     */
    private $indexPage;

    /**
     * @param IndexPageInterface $indexPage
     */
    public function __construct(IndexPageInterface $indexPage)
    {
        $this->indexPage = $indexPage;
    }

    /**
     * @When I filter them by :mugTypeValue mug type
     */
    public function iFilterThemByDoubleMugType($mugTypeValue)
    {
        $this->indexPage->open();
        $this->indexPage->setPaginating(100);
        $this->indexPage->filterByProductOptions(Criteria::fromQueryParameters(Product::class, ['mug_type' => $mugTypeValue]));
        $this->indexPage->filter();
    }

    /**
     * @When I filter them by :mugTypeValue mug type or sticker size :stickerSizeValue
     */
    public function iFilterThemByDoubleMugTypeAndStickerSize($mugTypeValue, $stickerSizeValue)
    {
        $this->indexPage->open();
        $this->indexPage->setPaginating(100);
        $this->indexPage->filterByProductOptions(Criteria::fromQueryParameters(Product::class, ['mug_type' => $mugTypeValue, 'sticker_size' => $stickerSizeValue]));
        $this->indexPage->filter();
    }

    /**
     * @When I filter them by stickier size :stickerSizeValue
     */
    public function iFilterThemByStickierSize($stickerSizeValue)
    {
        $this->indexPage->open();
        $this->indexPage->setPaginating(100);
        $this->indexPage->filterByProductOptions(Criteria::fromQueryParameters(Product::class, ['sticker_size' => $stickerSizeValue]));
        $this->indexPage->filter();
    }

    /**
     * @When /^I filter them by price between ("[^"]+") and ("[^"]+")$/
     */
    public function iFilterThemByPriceBetweenAnd($graterThan, $lessThan)
    {
        sleep(3);
        $this->indexPage->open();
        $this->indexPage->setPaginating(100);
        $this->indexPage->filterByPriceRange($graterThan, $lessThan);
        $this->indexPage->filter();
    }

    /**
     * @When I search for products with name :name
     */
    public function iSearchForProductsWithName($name)
    {
        sleep(3);
        $this->indexPage->open();
        $this->indexPage->setPaginating(100);
        $this->indexPage->search($name);
    }

    /**
     * @When I view the list of the products without filtering
     */
    public function iViewTheListOfTheProductsWithoutFiltering()
    {
        $this->indexPage->open(['per_page' => 100]);
    }

    /**
     * @Then I should see :numberOfProducts products on the list
     */
    public function iShouldSeeProductsOnTheList($numberOfProducts)
    {
        Assert::eq(count($this->indexPage->getAllProducts()), $numberOfProducts);
    }

    /**
     * @Then /^I should see products in order like "([^"]+)", "([^"]+)", "([^"]+)", "([^"]+)", "([^"]+)", "([^"]+)"$/
     */
    public function iShouldSeeProductsInOrderLike(...$productNames)
    {
        foreach ($this->indexPage->getAllProducts() as $position => $result) {
            if ($result->getText() !== $productNames[$position]) {
                throw new \RuntimeException(
                    sprintf(
                        'Sorting failed at position "%s" expected value was "%s", but got "%s"',
                        $position+1,
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
        $resultProductNames = array_map(function (NodeElement $productElement) {
            return $productElement->getText();
        }, $this->indexPage->getAllProducts());

        $expectedProductCount = count($expectedProductNames);
        $resultProductCount = count($resultProductNames);

        if ($expectedProductCount !== $resultProductCount) {
            throw new \RuntimeException(
                sprintf('Expected product count was "%s", got "%s"', $expectedProductCount, $resultProductCount)
            );
        }

        foreach ($expectedProductNames as $expectedProductName) {
            if (!in_array($expectedProductName, $resultProductNames)) {
                throw new \RuntimeException(sprintf(
                    'Expected product with name "%s", does not exist in search result. Got "%s"',
                    $expectedProductName,
                    implode(',', $resultProductNames)
                ));
            }
        }
    }
}
