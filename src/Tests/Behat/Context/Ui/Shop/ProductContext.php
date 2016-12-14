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
use Lakion\SyliusElasticSearchBundle\Tests\Behat\Page\Shop\Product\IndexPageInterface;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductContext implements Context
{
    /**
     * @var IndexPageInterface $indexPage
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
     * @Given I filter them by :color color
     */
    public function iFilterThemByRedColor($color)
    {
        $this->indexPage->filterBy(['color' => $color]);
    }

    /**
     * @Then I should be able to filter by :firstColor, :secondColor, :thirdColor and :fourthColor color
     */
    public function iShouldBeAbleToFilterByGreenRedBlackAndBlueColor(...$colors)
    {
        $availableColorFilters = $this->indexPage->getAvailableFiltersFor('colors');

        foreach ($colors as $color) {
            if (!in_array($color, $availableColorFilters, true)) {
                throw new \RuntimeException(sprintf('Expected "%s" color filter, buy got only "%s"', $color, implode(',', $availableColorFilters)));
            }
        }
    }
}
