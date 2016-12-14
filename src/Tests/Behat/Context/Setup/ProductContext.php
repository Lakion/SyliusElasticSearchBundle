<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Tests\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Sylius\Bundle\FixturesBundle\Fixture\FixtureInterface;
use Sylius\Component\Core\Model\TaxonInterface;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductContext implements Context
{
    /**
     * @var FixtureInterface
     */
    private $productFixture;

    /**
     * @var FixtureInterface
     */
    private $productOptionFixture;

    /**
     * @param FixtureInterface $productFixture
     * @param FixtureInterface $productOptionFixture
     */
    public function __construct(FixtureInterface $productFixture, FixtureInterface $productOptionFixture)
    {
        $this->productFixture = $productFixture;
        $this->productOptionFixture = $productOptionFixture;
    }

    /**
     * @Given the store has a lot of :taxon with different color :number of them are :color
     */
    public function theStoreHasALotOfWithDifferentColor(TaxonInterface $taxon, $number, $color)
    {
        $this->loadNumberOfRandomProducts(10);
        $this->loadCustomProductOptions(
            't_shirt_color',
            'T-Shirt color',
            [sprintf('t_shirt_color_%s', $color) => $color,]
        );

        $this->loadNumberOfCustomProducts($number, [
            'taxons' => [$taxon],
            'product_options' => ['t_shirt_color']
        ]);
    }

    /**
     * @Given the store has a lot of :taxon with black, gray, red color :number of them are :color
     */
    public function theStoreHasALotOfWithBlackGrayRedColorOfThemAreBlue(TaxonInterface $taxon, $number, $color)
    {
        $this->loadNumberOfRandomProducts(10);
        $this->loadCustomProductOptions('t_shirt_color', 'T-Shirt color', [
            't_shirt_color_black' => 'Black',
            't_shirt_color_gray' => 'Gray',
            't_shirt_color_white' => 'Red',
            sprintf('t_shirt_color_%s', $color) => $color,
        ]);

        $this->loadNumberOfCustomProducts($number, [
            'taxons' => [$taxon],
            'product_options' => ['t_shirt_color']
        ]);
    }

    /**
     * @param int $number
     */
    private function loadNumberOfRandomProducts($number)
    {
        $this->productFixture->load([
            'random' => (int) $number,
        ]);
    }

    /**
     * @param int $number
     * @param array $values
     */
    private function loadNumberOfCustomProducts($number, array $values)
    {
        $this->productFixture->load([
            'random' => (int) $number,
            'prototype' => $values,
        ]);
    }

    /**
     * @param string $code
     * @param string $name
     * @param array $values
     */
    private function loadCustomProductOptions($code, $name, array $values)
    {
        $this->productOptionFixture->load(['custom' => [
            [
                'name' => $name,
                'code' => $code,
                'values' => $values,
            ],
        ]]);
    }
}
