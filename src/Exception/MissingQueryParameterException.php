<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Exception;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class MissingQueryParameterException extends \RuntimeException
{
    /**
     * @param string $parameter
     * @param string $queryClass
     * @param \Exception|null $previousException
     */
    public function __construct($parameter, $queryClass, \Exception $previousException = null)
    {
        parent::__construct(sprintf('Missing "%s" parameter for "%s" query.', $parameter, $queryClass), 0, $previousException);
    }
}
