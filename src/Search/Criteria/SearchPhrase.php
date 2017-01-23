<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Search\Criteria;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class SearchPhrase
{
    /**
     * @var string
     */
    private $phrase;

    /**
     * @param string $phrase
     */
    public function __construct($phrase)
    {
        $this->phrase = $phrase;
    }

    /**
     * @return string
     */
    public function getPhrase()
    {
        return $this->phrase;
    }
}
