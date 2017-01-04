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
     * @When I filter them by :mugTypeValue mug type
     */
    public function iFilterThemByDoubleMugType($mugTypeValue)
    {
        $criteria = Criteria::fromQueryParameters(Product::class, ['product_option_code' => sprintf('mug_type_%s', $mugTypeValue)]);
        $this->match($criteria);
    }

    /**
     * @When I filter them by :mugTypeValue mug type or sticker size :stickerSizeValue
     */
    public function iFilterThemByDoubleMugTypeAndStickerSize($mugTypeValue, $stickerSizeValue)
    {
        $criteria = Criteria::fromQueryParameters(Product::class, ['product_option_code' => sprintf('mug_type_%s+sticker_size_%s', $mugTypeValue, $stickerSizeValue)]);
        $this->match($criteria);
    }

    /**
     * @When I filter them by stickier size :stickerSizeValue
     */
    public function iFilterThemByStickierSize($stickerSizeValue)
    {
        $criteria = Criteria::fromQueryParameters(Product::class, ['product_option_code' => sprintf('sticker_size_%s', $stickerSizeValue)]);
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
     * @When I filter them by :taxon taxon
     */
    public function iFilterThemByTaxon(TaxonInterface $taxon)
    {
        $criteria = Criteria::fromQueryParameters(Product::class, ['taxon_code' => $taxon->getCode()]);
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
     * @param Criteria $criteria
     */
    private function match(Criteria $criteria)
    {
        $result = $this->searchEngine->match($criteria);

        $this->sharedStorage->set('search_result', $result);
    }
}
