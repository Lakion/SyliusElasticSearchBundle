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
     * @When I filter them by :mugTypeValue mug type
     */
    public function iFilterThemByDoubleMugType($mugTypeValue)
    {
        $this->indexPage->open();
        $this->indexPage->filter(Criteria::fromQueryParameters(Product::class, ['mug_type' => $mugTypeValue]));
    }

    /**
     * @When I filter them by :mugTypeValue mug type or sticker size :stickerSizeValue
     */
    public function iFilterThemByDoubleMugTypeAndStickerSize($mugTypeValue, $stickerSizeValue)
    {
        $this->indexPage->open();
        $this->indexPage->filter(Criteria::fromQueryParameters(Product::class, ['mug_type' => $mugTypeValue, 'sticker_size' => $stickerSizeValue]));
    }

    /**
     * @When I filter them by stickier size :stickerSizeValue
     */
    public function iFilterThemByStickierSize($stickerSizeValue)
    {
        $this->indexPage->open();
        $this->indexPage->filter(Criteria::fromQueryParameters(Product::class, ['sticker_size' => $stickerSizeValue]));
    }

    /**
     * @When I view the list of the products without filtering
     */
    public function iViewTheListOfTheProductsWithoutFiltering()
    {
        $this->indexPage->open();
    }

    /**
     * @Then I should see :numberOfProducts products on the list
     */
    public function iShouldSeeProductsOnTheList($numberOfProducts)
    {
        $this->indexPage->open();
        Assert::eq($numberOfProducts, count($this->indexPage->getAllProducts()));
    }
}
