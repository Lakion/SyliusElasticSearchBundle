<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Tests\Behat\Services;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
interface SuspenderInterface
{
    /**
     * @param int $number
     * @param int $timeout
     *
     * @return bool
     */
    public function waitForLoadingNumberOfData($number, $timeout);
}
