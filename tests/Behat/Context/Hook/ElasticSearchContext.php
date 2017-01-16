<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Lakion\SyliusElasticSearchBundle\Behat\Context\Hook;

use Behat\Behat\Context\Context;
use FOS\ElasticaBundle\Index\Resetter;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ElasticSearchContext implements Context
{
    /**
     * @var Resetter
     */
    private $resetter;

    /**
     * @param Resetter $resetter
     */
    public function __construct(Resetter $resetter)
    {
        $this->resetter = $resetter;
    }

    /**
     * @BeforeScenario
     */
    public function resetElasticSearch()
    {
        $this->resetter->resetAllIndexes();
    }
}
