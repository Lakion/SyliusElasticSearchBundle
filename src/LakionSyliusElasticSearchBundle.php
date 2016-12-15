<?php

namespace Lakion\SyliusElasticSearchBundle;

use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;

final class LakionSyliusElasticSearchBundle extends AbstractResourceBundle
{
    const DRIVER_ELASTIC = 'elastic';

    /**
     * {@inheritdoc}
     */
    public function getSupportedDrivers()
    {
        return [SyliusResourceBundle::DRIVER_DOCTRINE_ORM, self::DRIVER_ELASTIC];
    }
}
