<?php

namespace Lakion\SyliusElasticSearchBundle\Exception;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class FilterSetConfigurationNotFoundException extends \RuntimeException
{
    /**
     * @param string $message
     * @param \Exception|null $previousException
     */
    public function __construct(
        $message = 'Filter set configuration not found.',
        \Exception $previousException = null
    ) {
        parent::__construct($message, 0, $previousException);
    }
}
