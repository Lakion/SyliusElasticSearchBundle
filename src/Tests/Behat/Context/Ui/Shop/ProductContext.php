<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Tests\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Tests\Behat\Page\Product\IndexPageInterface;
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
        $this->indexPage->open(['per_page' => 100]);
        $this->indexPage->filterByProductOptions(Criteria::fromQueryParameters(Product::class, ['product_option_code' => ['mug_type' => $mugTypeValue]]));
        $this->indexPage->filter();
    }

    /**
     * @When I filter them by :mugTypeValue mug type or sticker size :stickerSizeValue
     */
    public function iFilterThemByDoubleMugTypeAndStickerSize($mugTypeValue, $stickerSizeValue)
    {
        $this->indexPage->open(['per_page' => 100]);
        $this->indexPage->filterByProductOptions(Criteria::fromQueryParameters(Product::class, ['product_option_code' => ['mug_type' => $mugTypeValue, 'sticker_size' => $stickerSizeValue]]));
        $this->indexPage->filter();
    }

    /**
     * @When I filter them by stickier size :stickerSizeValue
     */
    public function iFilterThemByStickierSize($stickerSizeValue)
    {
        $this->indexPage->open(['per_page' => 100]);
        $this->indexPage->filterByProductOptions(Criteria::fromQueryParameters(Product::class, ['product_option_code' => ['sticker_size' => $stickerSizeValue]]));
        $this->indexPage->filter();
    }

    /**
     * @When /^I filter them by price between ("[^"]+") and ("[^"]+")$/
     */
    public function iFilterThemByPriceBetweenAnd($graterThan, $lessThan)
    {
        sleep(5);
        $this->indexPage->open(['per_page' => 100]);
        $this->indexPage->filterByPriceRange($graterThan, $lessThan);
        $this->indexPage->filter();
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
}
