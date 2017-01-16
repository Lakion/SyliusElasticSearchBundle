<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Lakion\SyliusElasticSearchBundle\Behat\Services;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
interface SuspenderInterface
{
    /**
     * @param int $number
     * @param int $timeoutSeconds
     *
     * @return bool
     */
    public function waitForLoadingNumberOfData($number, $timeoutSeconds);
}
