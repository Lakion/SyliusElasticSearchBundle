<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductHasOptionCodesFilter
{
    /**
     * @var array
     */
    private $codes;

    /**
     * @param array $codes
     */
    public function __construct(array $codes)
    {
        $this->codes = $codes;
    }

    /**
     * @return array
     */
    public function getCodes()
    {
        return $this->codes;
    }
}
