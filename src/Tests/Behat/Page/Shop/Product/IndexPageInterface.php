<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Tests\Behat\Page\Shop\Product;

use Sylius\Behat\Page\Shop\Product\IndexPageInterface as BaseIndexPageInterface;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
interface IndexPageInterface extends BaseIndexPageInterface
{
    /**
     * @param array $attributes
     */
    public function filterBy(array $attributes);

    /**
     * @param string $type
     *
     * @return array
     */
    public function getAvailableFiltersFor($type);
}
