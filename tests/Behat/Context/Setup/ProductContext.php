<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Lakion\SyliusElasticSearchBundle\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Tests\Lakion\SyliusElasticSearchBundle\Behat\Services\SuspenderInterface;
use Sylius\Bundle\FixturesBundle\Fixture\FixtureInterface;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductContext implements Context
{
    /**
     * @var FixtureInterface
     */
    private $bookProductFixture;

    /**
     * @var FixtureInterface
     */
    private $mugProductFixture;

    /**
     * @var FixtureInterface
     */
    private $stickerProductFixture;

    /**
     * @var SuspenderInterface
     */
    private $elasticSearchSuspender;

    /**
     * @param FixtureInterface $bookProductFixture
     * @param FixtureInterface $mugProductFixture
     * @param FixtureInterface $stickerProductFixture
     * @param SuspenderInterface $elasticSearchSuspender
     */
    public function __construct(
        FixtureInterface $bookProductFixture,
        FixtureInterface $mugProductFixture,
        FixtureInterface $stickerProductFixture,
        SuspenderInterface $elasticSearchSuspender
    ) {
        $this->bookProductFixture = $bookProductFixture;
        $this->mugProductFixture = $mugProductFixture;
        $this->stickerProductFixture = $stickerProductFixture;
        $this->elasticSearchSuspender = $elasticSearchSuspender;
    }

    /**
     * @Given the store has :mugsNumber Mugs, :stickersNumber Stickers and :booksNumber Books
     */
    public function theStoreHasAboutMugsAndStickers($mugsNumber, $stickersNumber, $booksNumber)
    {
        $this->mugProductFixture->load(['amount' => (int) $mugsNumber]);
        $this->stickerProductFixture->load(['amount' => (int) $stickersNumber]);
        $this->bookProductFixture->load(['amount' => (int) $booksNumber]);

        $this->elasticSearchSuspender->waitForLoadingNumberOfData((int) $mugsNumber + (int) $stickersNumber + (int) $booksNumber, 5);
    }
}
